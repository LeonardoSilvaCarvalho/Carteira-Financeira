<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        //Define os campos da tabela
        $this->forge->addField([
            'id'              => [
                'type' => 'INT',
                'auto_increment' => true // Auto incremento, chave primaria
            ],
            'account_number'  => [
                'type' => 'INT',
                'null' => false // Obrigatorio, usado como numero da conta bancaria
            ],
            'agency_number'   => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => '1234' // Codigo padrao da agencia
            ],
            'name'            => [
                'type' => 'VARCHAR',
                'constraint' => '100' // nome completo do usuario
            ],
            'email'           => [
                'type' => 'VARCHAR',
                'constraint' => '100' // E-mail unico para login e contato
            ],
            'cpf'             => [
                'type' => 'VARCHAR',
                'constraint' => '14' // CPF no formato 00.00.00.-00
            ],
            'password'        => [
                'type' => 'VARCHAR',
                'constraint' => '255' // Senha criptografada
            ],
            'balance'         => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0 // Saldo da conta, valor inicial 0
            ],
            'status'          => [
                'type' => 'ENUM',
                'constraint' => ['ativo', 'inativo'],
                'default' => 'ativo' // Indica se o usuario esta ativo ou inativo
            ],
            'created_at'      => [
                'type' => 'DATETIME' // data de criação do registro
            ],
            'updated_at'      => [
                'type' => 'DATETIME' // data da ultima atualização do registro
            ],
        ]);

        // Define a chave primaria como o campo "id"
        $this->forge->addKey('id', true);

        // Define os campos unicos  (nao podem se repetir)
        $this->forge->addUniqueKey('cpf'); // Garante que cada CPF seja unico
        $this->forge->addUniqueKey('email'); // Garante que cada Email seja unico
        $this->forge->addUniqueKey('account_number'); // Garante que cada numero de conta seja unico

        // Cria a tabela 'users' com os campos definidos acima
        $this->forge->createTable('users');
    }

    public function down()
    {
        // reverte a migração: exclui a tabela 'users'
        $this->forge->dropTable('users');
    }
}
