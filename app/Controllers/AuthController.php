<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LoginAttemptModel;

class AuthController extends BaseController
{
    public function login()
    {
        helper(['form']);
        return view('auth/login');
    }

    public function loginAuth()
    {
        $session = session();
        $model = new UserModel();
        $loginAttemptModel = new LoginAttemptModel();

        $agency  = $this->request->getVar('agency');
        $account = $this->request->getVar('account');
        $cpf     = $this->request->getVar('cpf');
        $password = $this->request->getVar('password');


        $attempts = $loginAttemptModel->getAttempts($agency, $account);

        if ($attempts >= 3) {
            $session->setFlashdata('msg', 'Muitas tentativas falhas. Aguarde 30 minutos para tentar novamente.');
            return redirect()->to('/');
        }

        $data = $model->where([
            'agency_number'  => $agency,
            'account_number' => $account,
            'cpf'            => $cpf,
        ])->first();

        if (!$data) {
            $loginAttemptModel->logAttempt($agency, $account, false);
            $session->setFlashdata('msg', 'Dados de login inválidos.');
            return redirect()->to('/');
        }

        if ($data['status'] !== 'ativo') {
            $session->setFlashdata('msg', 'Sua conta está inativa.');
            return redirect()->to('/');
        }

        if (password_verify($password, $data['password'])) {
            $session->set([
                'user_id'        => $data['id'],
                'name'           => $data['name'],
                'agency_number'  => $data['agency_number'],
                'account_number' => $data['account_number'],
            ]);

            setcookie('agency_number', $data['agency_number'], time() + (30 * 24 * 60 * 60), "/");
            setcookie('account_number', $data['account_number'], time() + (30 * 24 * 60 * 60), "/");

            $loginAttemptModel->logAttempt($agency, $account, true);
            return redirect()->to('/dashboard');
        } else {
            $loginAttemptModel->logAttempt($agency, $account, false);
            $session->setFlashdata('msg', 'Senha incorreta.');
            return redirect()->to('/');
        }
    }

    public function register()
    {
        helper(['form']);
        return view('auth/register');

    }

    public function save()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name'     => 'required|min_length[3]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'cpf'      => 'required|exact_length[14]|is_unique[users.cpf]',
                'password' => 'required|min_length[6]',
            ];

            $errors = [
                'cpf' => [
                    'exact_length' => 'CPF deve ter exatamente 14 caracteres.',
                    'is_unique'    => 'Este CPF já está em uso.',
                ],
                'email' => [
                    'is_unique' => 'Este e-mail já está em uso.',
                ],
            ];

            if (!$this->validate($rules, $errors)) {
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            $cpf = $this->request->getPost('cpf');
            if (!$this->validateCPF($cpf)) {
                return view('auth/register', [
                    'cpf_error' => 'CPF inválido.'
                ]);
            }

            $model = new UserModel();
            $accountNumber = $this->generateUniqueAccountNumber();
            $agencyNumber = '1234';

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

            $model->save($userData);


            return view('auth/register', [
                'swal'           => true,
                'name'           => $userData['name'],
                'agency_number'  => $agencyNumber,
                'account_number' => $accountNumber,
            ]);
        }

        return view('auth/register');
    }

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

    private function generateUniqueAccountNumber()
    {
        $model = new UserModel();
        $accountNumber = rand(10000000, 99999999);
        while ($model->where('account_number', $accountNumber)->first()) {
            $accountNumber = rand(10000000, 99999999);
        }
        return $accountNumber;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

}
