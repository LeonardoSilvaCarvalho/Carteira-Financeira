<table class="table table-striped lista-transferencia">
    <thead>
    <tr>
        <th>Descrição</th>
        <th>Tipo</th>
        <th>Valor</th>
        <th>Data</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($transactions) > 0): ?>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= esc($transaction['description']) ?></td>
                <td><?= esc($transaction['type']) ?></td>
                <td>R$ <?= number_format($transaction['amount'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Nenhuma transferência registrada.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
