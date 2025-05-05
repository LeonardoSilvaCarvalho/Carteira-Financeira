<ul class="list-group">
    <?php if (!empty($transactions)): ?>
        <?php foreach ($transactions as $transaction): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>Depósito de <b>R$ <?= number_format($transaction['amount'], 2, ',', '.') ?></b></span>
                <span class="text-muted"><?= date('d/m/Y - H:i', strtotime($transaction['created_at'])) ?></span>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="list-group-item text-center">Nenhum depósito realizado ainda.</li>
    <?php endif; ?>
</ul>


