<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginAttemptsTable extends Migration
{
    public function up()
    {
        // Define os campos da tabela 'login_attempts'
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true, // Identificador unico da tentativa
            ],
            'email'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null' => true, // Campo opcioanl para armazenar o e-amil usado na tentativa
            ],
            'success'       => [
                'type'           => 'TINYINT',
                'default'        => 0, // indica se o login foi bem-sucedido (0 = falha, 1 = sucesso)
            ],
            'attempted_at'  => [
                'type'           => 'DATETIME', // data e hora em que a tentativa de login foi feita
            ],
            'agency_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'after' => 'id',
                'null' => false, // numero da agencia usada na tentativa
            ],
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'after' => 'agency_number',
                'null' => false, // numero da conta usada na tentativa
            ],
        ]);
        // define a chave primaria
        $this->forge->addPrimaryKey('id');

        // cria a tabela 'login_attempts' com os campos definidos
        $this->forge->createTable('login_attempts');
    }

    public function down()
    {
        // reverte a migração: exclui a tabela
        $this->forge->dropTable('login_attempts');
    }

    // OBSERVAÇÕES IMPORTANTES:
    //caso o migrador nao aplique corretamente os campos 'agency_number' e account_number',
    // use o script abaixo diretamente no banco de dados:
    /*
    ALTER TABLE login_attempts
    ADD COLUMN agency_number VARCHAR(10) NOT NULL AFTER id,
    ADD COLUMN account_number VARCHAR(20) NOT NULL AFTER agency_number,
    MODIFY COLUMN email VARCHAR(255) NULL;
    */
}
