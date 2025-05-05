<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'email',
        'cpf',
        'password',
        'balance',
        'account_number',
        'agency_number',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules = [
        'name'     => 'required|min_length[3]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'cpf'      => 'required|exact_length[14]|is_unique[users.cpf]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'cpf' => [
            'is_unique' => 'Este CPF já está em uso.',
            'exact_length' => 'CPF deve ter exatamente 14 caracteres.',
        ],
        'email' => [
            'is_unique' => 'Este e-mail já está em uso.',
        ],
    ];
}
