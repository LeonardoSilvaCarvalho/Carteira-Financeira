<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    //define o nome da tabela no banco de dados
    protected $table            = 'users';
    //define a chave primaria da tabela
    protected $primaryKey       = 'id';
    // informa que a chave primaria e auto incrementavel
    protected $useAutoIncrement = true;
    // define o tipo de retorno ao buscar registros (array ao invez de objetos)
    protected $returnType       = 'array';
    // protege os campos contra inserção/atualização nao autorizada
    protected $protectFields    = true;

    // campos que podem ser preenchidos via insert ou update
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

    //habilita timestamp automaticos para created_at e updated_at
    protected $useTimestamps = true;
    // define o formato de dataq utilizado nos campos
    protected $dateFormat    = 'datetime';
    //campos para armazemaneto data de criação e atualização
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // regras de validação aplicadas automaicamente ao inserir ou atualizar
    protected $validationRules = [
        'name'     => 'required|min_length[3]',                               // Nome é obrigatório e com no mínimo 3 caracteres
        'email'    => 'required|valid_email|is_unique[users.email]',          // E-mail válido e único
        'cpf'      => 'required|exact_length[14]|is_unique[users.cpf]',       // CPF obrigatório, com 14 caracteres e único
        'password' => 'required|min_length[6]',                               // Senha obrigatória com no mínimo 6 caracteres
    ];

    //mensagens personalizadas para erros de validação
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
