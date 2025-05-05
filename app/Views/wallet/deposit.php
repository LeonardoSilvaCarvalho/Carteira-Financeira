<?= view('layout/header', ['title' => 'Área de Depósitos']) ?>
<?= view('layout/sidebar') ?>

<!-- Área principal -->
<div class="col-md-10 p-4">
    <h2 id="depositTitle" class="mb-4 fw-bold text-secondary">Área de Depósitos</h2>

    <div class="row">
        <div class="col-md-6">
            <div id="animatedCard" class="card text-white border-0 shadow-lg rounded-4 animate__animated"
                 style="height: 300px; background: linear-gradient(135deg, #1e3c72, #2a5298); position: relative; overflow: hidden; font-family: 'Courier New', monospace;">

                <!-- Chip -->
                <div style="position: absolute; top: 20px; left: 20px; width: 50px; height: 35px; background-color: rgba(255,255,255,0.8); border-radius: 8px;"></div>

                <!-- Selo estilo Mastercard -->
                <div style="position: absolute; top: 20px; right: 20px; display: flex; align-items: center; gap: 5px;">
                    <div style="width: 30px; height: 30px; background-color: #f79e1b; border-radius: 50%; opacity: 0.9;"></div>
                    <div style="width: 30px; height: 30px; background-color: #eb001b; border-radius: 50%; opacity: 0.9; margin-left: -15px;"></div>
                </div>

                <!-- Conteúdo do cartão -->
                <div class="card-body d-flex flex-column justify-content-center h-100 pt-5 px-4">
                    <!-- Número do cartão -->
                    <div class="fs-4 fw-bold letter-spacing text-white mt-3">1234 5678 **** ****</div>

                    <span class="text-uppercase fw-bold text-white-50 mb-1 fs-3">Saldo disponível</span>
                    <p class="display-5 fw-bolder font-monospace d-flex align-items-center">
                        R$
                        <span id="balanceHidden" class="ms-2">*****</span>
                        <span id="balanceDisplay" class="ms-2 d-none"><?= number_format($user['balance'], 2, ',', '.') ?></span>
                        <button id="toggleBalance" type="button" class="btn btn-sm btn-outline-light rounded-circle ms-3">
                            <i id="toggleIcon" class="bi bi-eye-fill"></i>
                        </button>
                    </p>
                    <div class="d-flex justify-content-between text-white-50 small">
                        <div>
                            <div>Nome do Titular</div>
                            <div><?= esc(session()->get('name')) ?></div>
                        </div>
                        <div class="text-end">
                            <div>12/30</div>
                            <div>Validade</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Depósito -->
        <div class="col-md-6" id="formCard">
            <div class="card shadow border-0 rounded-4 h-100 animate__animated">
                <div class="card-body d-flex flex-column justify-content-center">
                    <form id="depositForm" method="POST" action="/deposit" class="d-flex flex-column gap-3">
                        <input name="amount" type="number" step="0.01" min="0.01"
                               placeholder="Digite o valor do depósito"
                               class="form-control form-control-lg rounded-pill shadow-sm"
                               required>
                        <button type="submit" class="btn btn-success btn-lg rounded-pill shadow fw-bold">
                            Depositar
                        </button>
                        <div class="d-flex justify-content-around gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('depositForm').amount.value='50.00'">R$ 50,00</button>
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('depositForm').amount.value='100.00'">R$ 100,00</button>
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('depositForm').amount.value='200.00'">R$ 200,00</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Depósitos -->
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-primary text-white">
                    <h5>Últimos Depósitos</h5>
                </div>
                <div class="card-body">
                    <?= view('partials/deposit_table', compact('transactions', 'pager', 'total')) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?= view('layout/footer') ?>
