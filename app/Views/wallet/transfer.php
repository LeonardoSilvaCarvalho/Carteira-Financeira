<?= view('layout/header', ['title' => 'Transferência Bancária']) ?>
<?= view('layout/sidebar') ?>

<div class="col-md-10 p-4">
    <div class="container">
        <h2 class="mb-4 fw-bold text-secondary">Transferência Bancária</h2>

        <!-- Saldo disponível -->
        <div class="mb-4">
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <strong>Saldo disponível: </strong>
                    <span id="balanceTransfer">*****</span>
                </div>
                <button class="btn btn-sm btn-outline-primary" id="toggleTransferBalance">
                    <i class="bi bi-eye-fill" id="iconTransferBalance"></i>
                </button>
            </div>
        </div>

        <!-- Formulário de Transferência -->
        <div class="card shadow p-4 mb-5 rounded-4">
            <form id="transferForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="toName" class="form-label">Nome do Destinatário</label>
                        <input type="text" name="toName" class="form-control" placeholder="Ex: João da Silva" required>
                    </div>

                    <div class="col-md-3">
                        <label for="toAccount" class="form-label">Nº da Conta</label>
                        <input type="text" name="toAccount[number]" class="form-control" placeholder="00012345-6" required>
                    </div>

                    <div class="col-md-3">
                        <label for="toAgency" class="form-label">Agência</label>
                        <input type="text" name="toAgency" class="form-control" placeholder="1234" required>
                    </div>

                    <div class="col-md-4">
                        <label for="amount" class="form-label">Valor a Transferir</label>
                        <input type="number" name="amount" step="0.01" class="form-control" placeholder="Ex: 150.00" required>
                    </div>

                    <div class="col-md-12 d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send-fill me-1"></i> Confirmar Transferência
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabela de Transações -->
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Últimas Transferências
                </h5>
                <a href="/revers" class="btn btn-light btn-sm text-primary d-flex align-items-center">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reverter transfêrencia
                </a>
            </div>
            <div class="card-body">
                <?= view('partials/transaction_table', ['transactions' => $transactions]) ?>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>

<!-- Script para alternar visibilidade do saldo -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const balanceEl = document.getElementById('balanceTransfer');
        const toggleBtn = document.getElementById('toggleTransferBalance');
        const icon = document.getElementById('iconTransferBalance');
        let visible = false;

        // Saldo real embutido como data-atributo seguro
        const realBalance = '<?= number_format($user['balance'], 2, ',', '.') ?>';

        toggleBtn.addEventListener('click', function () {
            visible = !visible;
            balanceEl.textContent = visible ? 'R$ ' + realBalance : '*****';
            icon.className = visible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        });
    });
</script>
