<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LoginAttemptModel;

class AuthController extends BaseController
{
    // Exibe a tela de login
    public function login()
    {
        helper(['form']);
        return view('auth/login');
    }

    // Processa a autenticação do usuario
    public function loginAuth()
    {
        $session = session();
        $model = new UserModel();
        $loginAttemptModel = new LoginAttemptModel();

        // Coleta od dados do formulario
        $agency  = $this->request->getVar('agency');
        $account = $this->request->getVar('account');
        $cpf     = $this->request->getVar('cpf');
        $password = $this->request->getVar('password');

        // Verifica se o usuario excedeu o número de tentativas de acesso
        $attempts = $loginAttemptModel->getAttempts($agency, $account);

        if ($attempts >= 3) {
            $session->setFlashdata('msg', 'Muitas tentativas falhas. Aguarde 30 minutos para tentar novamente.');
            return redirect()->to('/');
        }

        // Busca usuario com base em agência, conta e CPF
        $data = $model->where([
            'agency_number'  => $agency,
            'account_number' => $account,
            'cpf'            => $cpf,
        ])->first();

        // Usuario nao encontrado
        if (!$data) {
            $loginAttemptModel->logAttempt($agency, $account, false);
            $session->setFlashdata('msg', 'Dados de login inválidos.');
            return redirect()->to('/');
        }

        // Conta inativada
        if ($data['status'] !== 'ativo') {
            $session->setFlashdata('msg', 'Sua conta está inativa.');
            return redirect()->to('/');
        }

        // Verifica a senha com hash
        if (password_verify($password, $data['password'])) {
            // Armazena dados essenciais na sessão
            $session->set([
                'user_id'        => $data['id'],
                'name'           => $data['name'],
                'agency_number'  => $data['agency_number'],
                'account_number' => $data['account_number'],
            ]);

            // Define cookies para facilitar preenchimento automatico posteriores
            setcookie('agency_number', $data['agency_number'], time() + (30 * 24 * 60 * 60), "/");
            setcookie('account_number', $data['account_number'], time() + (30 * 24 * 60 * 60), "/");

            // Registra tentativa de login bem-sucedida
            $loginAttemptModel->logAttempt($agency, $account, true);
            return redirect()->to('/dashboard');
        } else {
            // senha incorreta
            $loginAttemptModel->logAttempt($agency, $account, false);
            $session->setFlashdata('msg', 'Senha incorreta.');
            return redirect()->to('/');
        }
    }

    // Exibe a tela de cadastro
    public function register()
    {
        helper(['form']);
        return view('auth/register');

    }

    // Procesa e salva o cadastro do usuario
    public function save()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            // Validações basicas dos campos de cadastro
            $rules = [
                'name'     => 'required|min_length[3]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'cpf'      => 'required|exact_length[14]|is_unique[users.cpf]',
                'password' => 'required|min_length[6]',
            ];

            // Mensagens personalizadas de erro
            $errors = [
                'cpf' => [
                    'exact_length' => 'CPF deve ter exatamente 14 caracteres.',
                    'is_unique'    => 'Este CPF já está em uso.',
                ],
                'email' => [
                    'is_unique' => 'Este e-mail já está em uso.',
                ],
            ];

            // Validação falho redireciona para a tela de cadastro com a msg de erro
            if (!$this->validate($rules, $errors)) {
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            // Validação extra para o CPF
            $cpf = $this->request->getPost('cpf');
            if (!$this->validateCPF($cpf)) {
                return view('auth/register', [
                    'cpf_error' => 'CPF inválido.'
                ]);
            }


            $model = new UserModel();
            $accountNumber = $this->generateUniqueAccountNumber();
            $agencyNumber = '1234'; // Agencia fixa para todos os usuarios

            // dados do novo usuario
            $userData = [
                'name'           => $this->request->getPost('name'),
                'email'          => $this->request->getPost('email'),
                'cpf'            => $cpf,
                'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'account_number' => $accountNumber,
                'agency_number'  => $agencyNumber,
                'balance'        => 0.00,
                'status'         => 'ativo',
            ];

            // Salva usuario no banco de dados
            $model->save($userData);

            // retorna para a tela com dados do usuario e aviso de sucesso (via SweerAlert)
            return view('auth/register', [
                'swal'           => true,
                'name'           => $userData['name'],
                'agency_number'  => $agencyNumber,
                'account_number' => $accountNumber,
            ]);
        }

        return view('auth/register');
    }

    // Validação algoritimica de CPF (baseado em digitos verificadores)
    private function validateCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    // Geração de numer de conta unico (evita duplicação)
    private function generateUniqueAccountNumber()
    {
        $model = new UserModel();
        $accountNumber = rand(10000000, 99999999);
        // Loop ate encontrar um numero nao utilizado
        while ($model->where('account_number', $accountNumber)->first()) {
            $accountNumber = rand(10000000, 99999999);
        }
        return $accountNumber;
    }

    // Realiza o logout do usuario
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

}
/*
 Ultilização do BCRYPT(password_hash) para a segurança da senha

 Sistema de tentativas de login com limite e tempo de bloqueio para evitar ataques de força bruta

 Validação manual de CPF com algoritmo oficial.

 Cookies e sessão para facilitar a experiencia do usuario sem compromoter a segurança.

 Criação de numeros de conta unico com verificação de duplicidade.

 Respostas dinamicas e feedbacks para o usuario com mensagens claras.

*/