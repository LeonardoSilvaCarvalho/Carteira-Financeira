<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'account_number'  => ['type' => 'INT', 'null' => false],
            'agency_number'   => ['type' => 'VARCHAR', 'constraint' => '10', 'default' => '1234'],
            'name'            => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'           => ['type' => 'VARCHAR', 'constraint' => '100'],
            'cpf'             => ['type' => 'VARCHAR', 'constraint' => '14'],
            'password'        => ['type' => 'VARCHAR', 'constraint' => '255'],
            'balance'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'status'          => ['type' => 'ENUM', 'constraint' => ['ativo', 'inativo'], 'default' => 'ativo'],
            'created_at'      => ['type' => 'DATETIME'],
            'updated_at'      => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('cpf');
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('account_number');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
