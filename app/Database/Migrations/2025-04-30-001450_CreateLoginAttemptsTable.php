<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginAttemptsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null' => true,
            ],
            'success'       => [
                'type'           => 'TINYINT',
                'default'        => 0, // 0 = Falha, 1 = Sucesso
            ],
            'attempted_at'  => [
                'type'           => 'DATETIME',
            ],
            'agency_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'after' => 'id',
                'null' => false,
            ],
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'after' => 'agency_number',
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('login_attempts');
    }

    public function down()
    {
        $this->forge->dropTable('login_attempts');
    }


    //caso nao o migrate nao crie as colunas agency e account - todar o script na base de dados manual
/*
ALTER TABLE login_attempts
ADD COLUMN agency_number VARCHAR(10) NOT NULL AFTER id,
ADD COLUMN account_number VARCHAR(20) NOT NULL AFTER agency_number,
MODIFY COLUMN email VARCHAR(255) NULL;
*/
}
