<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactions extends Migration
{
    public function up()
    {
        // Define os campos da tabela 'transactions'
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true // Identificador único da transação
            ],
            'user_id' => [
                'type' => 'INT' // Id do usuario que realizou a transação
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['deposit', 'transfer', 'reversal'] // Tipo de transação: deposito, transferencia ou reversão
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2' // Valor de transação com ate 2 casa decimais
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true // define opcional da transação
            ],
            'related_user_id' => [
                'type' => 'INT',
                'null' => true // em caso de tranferencia, armazena o ID do usuario destino
            ],
            'is_reversed' => [
                'type' => 'BOOLEAN',
                'default' => false // indica se a transação foi revertida
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true // data/hora em que a transação foi criada
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true // data/hora da ultima atualização da transação
            ],
        ]);

        // Define a chave primaria como o campo 'id'
        $this->forge->addKey('id', true);

        // Cria a tabela 'transactions' com os campos definidos
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        // reverte a migração: exclui a tabela 'transactions'
        $this->forge->dropTable('transactions');
    }
}
