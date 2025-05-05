<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT'],
            'type' => ['type' => 'ENUM', 'constraint' => ['deposit', 'transfer', 'reversal']],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'related_user_id' => ['type' => 'INT', 'null' => true],
            'is_reversed' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
