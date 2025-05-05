<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('transactions')->truncate();
        $this->db->table('users')->truncate();
        $this->db->table('login_attempts')->truncate();

        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@gmail.com',
                'cpf' => '688.450.740-30',
                'agency_number' => '1234',
                'account_number' => '1001',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'balance' => 1000.00,
                'created_at' => date('Y-m-d H:i:s')
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

        $this->db->table('users')->insertBatch($users);

    }
}
