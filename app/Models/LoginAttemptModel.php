<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginAttemptModel extends Model
{
    protected $table = 'login_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['agency_number', 'account_number', 'success', 'attempted_at'];

    public function logAttempt($agency, $account, $success)
    {
        $this->insert([
            'agency_number'  => $agency,
            'account_number' => $account,
            'success'        => $success ? 1 : 0,
            'attempted_at'   => date('Y-m-d H:i:s'),
        ]);
    }

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

