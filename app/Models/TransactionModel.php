<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    // Nome da tabela no banco de dados
    protected $table            = 'transactions';
    // chave primaria da tabela
    protected $primaryKey       = 'id';
    // define se o campo primario e auto-incrementavel
    protected $useAutoIncrement = true;
    // Define o tipo de retorno das consultas (array ao invés de objeto)
    protected $returnType       = 'array';
    // Desativa a exclusão lógica (soft deletes)
    protected $useSoftDeletes   = false;
    // Protege os campos contra preenchimento indevido
    protected $protectFields    = true;

    // Campos que podem ser preenchidos ao inserir ou atualizar registros
    protected $allowedFields = [
        'user_id',          // ID do usuário que realizou a transação
        'type',             // Tipo da transação: deposit, transfer, reversal
        'amount',           // Valor da transação
        'description',      // Descrição opcional
        'is_reversed',      // Indica se essa transação já foi revertida
        'related_user_id',  // ID do outro usuário envolvido (em caso de transferência)
        'created_at',       // Data de criação
    ];

    // Permite ou não inserções com campos vazios (falso por padrão)
    protected bool $allowEmptyInserts = false;
    // Atualiza apenas os campos que foram realmente alterados
    protected bool $updateOnlyChanged = true;

    // Casts personalizados (não utilizado aqui, mas disponível para uso)
    protected array $casts = [];
    // Handlers para os casts (opcional, se você quiser tipos complexos como JSON)
    protected array $castHandlers = [];

    // Configurações de datas e timestamps automáticos
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // Mesmo com soft delete desativado, o campo está declarado (caso ative futuramente)
    protected $deletedField  = 'deleted_at';

    // Regras de validação (nenhuma por enquanto, mas pode ser adicionada)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks (eventos antes/depois de ações do banco)
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
