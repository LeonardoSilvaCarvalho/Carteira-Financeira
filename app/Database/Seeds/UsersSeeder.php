<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {

        // Limpa as tabelas antes de popular com novos dados
        // Isso garante que os testes comecem sempre com um estado consistente
        $this->db->table('transactions')->truncate(); // remove todas as transações
        $this->db->table('users')->truncate(); // remove todos os usuarios
        $this->db->table('login_attempts')->truncate(); // remove todas as tentativas de login

        // define um array de usuarios para inserir
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@gmail.com',
                'cpf' => '688.450.740-30',
                'agency_number' => '1234',
                'account_number' => '1001',
                'password' => password_hash('123456', PASSWORD_DEFAULT), // senha segura usando hack
                'balance' => 1000.00, // saldo inicial
                'created_at' => date('Y-m-d H:i:s') // data de criação automatica
            ],
            [
                'name' => 'Maria Souza',
                'email' => 'maria@gmail.com',
                'cpf' => '534.636.710-18',
                'agency_number' => '1234',
                'account_number' => '1002',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'balance' => 1500.00,
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        // insere todos os usuarios no banco de uma vez so
        $this->db->table('users')->insertBatch($users);

    }
}
