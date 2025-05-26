<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TransactionModel;
use CodeIgniter\Controller;

class WalletController extends BaseController
{
    private UserModel $userModel;
    private TransactionModel $transactionModel;

    public function __construct()
    {
        // INstacia os modelos para uso nos metodos
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
    }

    // Retorna o usuario logado apartir da sessão
    private function getLoggedUser()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return null;
        }

        return $user;
    }

    // Exibe o dashboard do usuario com as transações recentes
    public function dashboard()
    {
        $user = $this->getLoggedUser();
        if (!$user) {
            return redirect()->to('/')->with('msg', 'Usuário não encontrado.');
        }

        $transactions = $this->transactionModel
            ->where('user_id', $user['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('wallet/dashboard', ['user' => $user, 'transactions' => $transactions]);
    }

    // Exibe a tela de depositos com paginação
    public function depositView()
    {
        helper(['form']);
        $user = $this->getLoggedUser();
        if (!$user) {
            return redirect()->to('/')->with('msg', 'Usuário não encontrado.');
        }

        $perPage = 5;
        $page = $this->request->getVar('page') ?? 1;

        $transactions = $this->transactionModel
            ->where('user_id', $user['id'])
            ->where('type', 'deposit')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', (int) $page);

        $total = $this->transactionModel
            ->where('user_id', $user['id'])
            ->where('type', 'deposit')
            ->countAllResults();

        return view('wallet/deposit', [
            'user' => $user,
            'transactions' => $transactions,
            'pager' => $this->transactionModel->pager,
            'total' => $total
        ]);
    }

    // Processa um deposito enviado via AJAX
    public function deposit()
    {
        $data = $this->request->getJSON();
        $amount = (float) $data->amount;
        $password = $data->password;

        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valor inválido.']);
        }

        // Verifica se a senha esta correta
        $passwordCheck = $this->verifyPasswordHelper($password);
        if (!$passwordCheck['success']) {
            return $this->response->setJSON(['success' => false, 'message' => $passwordCheck['message']]);
        }

        $user = $this->getLoggedUser();
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuário inválido.']);
        }

        // atualiza o salvo do usuario
        $newBalance = $user['balance'] + $amount;
        $this->userModel->update($user['id'], ['balance' => $newBalance]);

        // Registra a transação
        $this->transactionModel->save([
            'user_id' => $user['id'],
            'type' => 'deposit',
            'amount' => $amount,
            'description' => 'Depósito realizado'
        ]);

        // Atualiza a lista de transações na view
        $transactions = $this->transactionModel
            ->where('user_id', $user['id'])
            ->where('type', 'deposit')
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $transactionsHtml = view('partials/deposit_table', [
            'transactions' => $transactions,
            'pager' => 1
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Depósito realizado com sucesso.',
            'new_balance' => $newBalance,
            'transactions_html' => $transactionsHtml
        ]);
    }

    // Exibe a tela de tranferencia
    public function transferView()
    {
        $user = $this->getLoggedUser();
        if (!$user) {
            return redirect()->to('/')->with('msg', 'Usuário não encontrado.');
        }

        $perPage = 5;
        $page = $this->request->getVar('page') ?? 1;

        $transactions = $this->transactionModel
            ->where('user_id', $user['id'])
            ->where('type', 'transfer')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', (int) $page);

        $total = $this->transactionModel
            ->where('user_id', $user['id'])
            ->countAllResults();

        return view('wallet/transfer', [
            'transactions' => $transactions,
            'user' => $user,
            'pager' => $this->transactionModel->pager,
            'total' => $total
        ]);
    }

    // Processa a tranferencia entre usuarios
    public function transfer()
    {
        $data = $this->request->getJSON();
        $amount = (float) $data->amount;
        $password = $data->password;
        $toAccount = $data->toAccount;
        $toName = $data->toName;
        $toAgency = $data->toAgency;

        if ($amount <= 0 || !$toAccount || !$toName || !$toAgency) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dados inválidos para transferência.']);
        }

        $passwordCheck = $this->verifyPasswordHelper($password);
        if (!$passwordCheck['success']) {
            return $this->response->setJSON(['success' => false, 'message' => $passwordCheck['message']]);
        }

        $fromUser = $this->getLoggedUser();
        if (!$fromUser || $fromUser['balance'] < $amount) {
            return $this->response->setJSON(['success' => false, 'message' => 'Saldo insuficiente ou usuário inválido.']);
        }

        // Busca o destinatario
        $toUser = $this->userModel->where('account_number', $toAccount->number)->first();
        if (!$toUser || $toUser['name'] !== $toName || $toUser['agency_number'] !== $toAgency) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dados do destinatário não coincidem.']);
        }

        // transação segura
        $this->userModel->transStart();

        // debita do remetente e credita no destinatario
        $this->userModel->update($fromUser['id'], ['balance' => $fromUser['balance'] - $amount]);
        $this->userModel->update($toUser['id'], ['balance' => $toUser['balance'] + $amount]);

        // Salva a transação
        $this->transactionModel->save([
            'user_id' => $fromUser['id'],
            'type' => 'transfer',
            'amount' => $amount,
            'description' => 'Transferência para ' . esc($toUser['name']),
            'related_user_id' => $toUser['id']
        ]);

        // Atualiza as transações exibidas
        $transactions = $this->transactionModel
            ->where('user_id', $fromUser['id'])
            ->where('type', 'transfer')
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $transactionsHtml = view('partials/transaction_table', ['transactions' => $transactions]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transferência realizada com sucesso.',
            'new_balance' => $fromUser['balance'] - $amount,
            'transactions_html' => $transactionsHtml
        ]);
    }

    // Tela que lista transações reversiveis (Somente tranferencias)
    public function reverseRequestView()
    {
        $user = $this->getLoggedUser();
        if (!$user) {
            return redirect()->to('/')->with('msg', 'Usuário não encontrado.');
        }

        $transactions = $this->transactionModel
            ->where('user_id', $user['id'])
            ->where('type', 'transfer')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Marca quais transações ja foram revertidas
        $reversedMap = [];
        foreach ($transactions as $tx) {
            if ($tx['type'] === 'reversal' && $tx['is_reversed']) {
                $reversedMap[$tx['is_reversed']] = true;
            }
        }

        foreach ($transactions as &$tx) {
            $tx['is_reversed'] = isset($reversedMap[$tx['id']]);
        }

        return view('wallet/reverse', ['transactions' => $transactions]);
    }

    // Processa a reversão de uma transação
    public function reverseTransaction($transactionId)
    {
        $password = $this->request->getPost('password');
        $user = $this->getLoggedUser();

        // Valida senha
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Senha incorreta. Reversão cancelada.']);
        }

        $original = $this->transactionModel->find($transactionId);
        if (!$original || $original['user_id'] != $user['id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transação inválida.']);
        }

        if (!in_array($original['type'], ['deposit', 'transfer']) || !empty($original['is_reversed'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transação não pode ser revertida.']);
        }

        $this->userModel->transStart();

        if ($original['type'] === 'deposit') {
            // Verifica se ha saldo suficiente para reverter o deposito
            if ($user['balance'] < $original['amount']) {
                $this->userModel->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Saldo insuficiente para reverter.']);
            }

            $this->userModel->update($user['id'], [
                'balance' => $user['balance'] - $original['amount']
            ]);
        } else {
            // Reverte uma tranferencia
            $toUser = $this->userModel->find($original['related_user_id']);
            if (!$toUser || $toUser['balance'] < $original['amount']) {
                $this->userModel->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Não é possível reverter a transferência.']);
            }

            $this->userModel->update($user['id'], ['balance' => $user['balance'] + $original['amount']]);
            $this->userModel->update($toUser['id'], ['balance' => $toUser['balance'] - $original['amount']]);
        }

        // Marca a transação como revertida
        $this->transactionModel->update($transactionId, ['is_reversed' => true, 'type' => 'reversal']);

        $this->userModel->transComplete();

        return $this->response->setJSON(['success' => true, 'message' => 'Transação revertida com sucesso.']);
    }

    // verifica senha via AJAX
    public function verifyPassword()
    {
        $password = $this->request->getVar('password');
        $user = $this->getLoggedUser();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Senha incorreta.']);
        }

        return $this->response->setJSON(['success' => true]);
    }

    // Função auxiliar para verificar senha em outros metodos
    private function verifyPasswordHelper($password)
    {
        $user = $this->getLoggedUser();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Senha incorreta.'];
        }

        return ['success' => true];
    }
}
