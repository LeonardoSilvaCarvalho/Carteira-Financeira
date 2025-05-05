<table class="table table-hover align-middle shadow-sm rounded overflow-hidden">
    <thead class="table-primary text-white">
    <tr>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Data</th>
        <th>Valor</th>
        <th>Ações</th>
    </tr>
    </thead>

    <?php
    $reversedIds = array_column(
        array_filter($transactions,
            fn($t) => $t['type'] === 'reversal'
                && !empty($t['related_transaction_id'])),
        'related_transaction_id'
    );
    ?>

    <tbody>
    <?php foreach ($transactions as $transaction): ?>
        <?php
        if (
            $transaction['type'] === 'reversal'
            || in_array($transaction['id'], $reversedIds)
            || (!empty($transaction['is_reversed']) && $transaction['is_reversed'] == true)
        ) {
            continue;
        }
        ?>
        <tr>
            <td><?= ucfirst(esc($transaction['type'])) ?></td>
            <td><?= esc($transaction['description']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
            <td><span class="badge bg-success fs-6">R$ <?= number_format($transaction['amount'], 2, ',', '.') ?></span></td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="reverseTransaction(<?= $transaction['id'] ?>)">
                    <i class="bi bi-arrow-counterclockwise"></i> Reverter
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
