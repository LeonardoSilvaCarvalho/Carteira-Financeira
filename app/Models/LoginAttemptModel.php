<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginAttemptModel extends Model
{
    // nome da tabela
    protected $table = 'login_attempts';
    // chave primaria
    protected $primaryKey = 'id';

    // Campos permitidos para inserção/atualização
    protected $allowedFields = [
        'agency_number',      // Agência do usuário
        'account_number',     // Conta do usuário
        'success',            // 0 = falha, 1 = sucesso
        'attempted_at',       // Data e hora da tentativa
    ];

    /**
     * Registra uma tentativa de login no banco.
     *
     * @param string $agency  Agência do usuário.
     * @param string $account Conta do usuário.
     * @param bool   $success Indica se a tentativa foi bem-sucedida (true) ou não (false).
     */
    public function logAttempt($agency, $account, $success)
    {
        $this->insert([
            'agency_number'  => $agency,
            'account_number' => $account,
            'success'        => $success ? 1 : 0,
            'attempted_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Conta o número de tentativas falhas nas últimas 30 minutos
     * para uma determinada agência e conta.
     *
     * @param string $agency  Agência do usuário.
     * @param string $account Conta do usuário.
     * @return int Número de tentativas falhas recentes.
     */
    public function getAttempts($agency, $account)
    {
        $timeLimit = date('Y-m-d H:i:s', strtotime('-30 minutes'));

        return $this->where('agency_number', $agency)
            ->where('account_number', $account)
            ->where('success', 0)
            ->where('attempted_at >', $timeLimit)
            ->countAllResults();
    }
}

