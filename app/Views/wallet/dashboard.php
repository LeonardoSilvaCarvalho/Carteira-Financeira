<?= view('layout/header', ['title' => 'Dashboard']) ?>
<?= view('layout/sidebar') ?>

<div class="col-md-10 p-4">
    <h2 id="depositTitle" class="mb-4 fw-bold text-secondary">Olá, Bem-vindo!</h2>
    <hr>
    <div class="row">

        <div class="col-md-5 mt-2 mb-3">
            <div class="card text-white border-0 shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-1s"
                 style="height: 250px; background: linear-gradient(135deg, #1e3c72, #2a5298); position: relative; overflow: hidden;">

                <!-- Chip -->
                <div style="position: absolute; top: 20px; left: 20px; width:43px; height: 35px; background-color: rgba(255,255,255,0.8); border-radius: 8px;"></div>

                <!-- Selo estilo Mastercard -->
                <div style="position: absolute; top: 20px; right: 20px; display: flex; align-items: center; gap: 5px;">
                    <div style="width: 30px; height: 30px; background-color: #f79e1b; border-radius: 50%; opacity: 0.9;"></div>
                    <div style="width: 30px; height: 30px; background-color: #eb001b; border-radius: 50%; opacity: 0.9; margin-left: -15px;"></div>
                </div>

                <!-- Conteúdo do cartão -->
                <div class="card-body d-flex flex-column justify-content-center h-100">
                    <span class="text-uppercase fw-bold text-white-50 mt-5 fs-6">Saldo Atual</span>
                    <p class="display-5 fw-bolder font-monospace d-flex align-items-center">
                        R$
                        <span id="balanceHidden" class="ms-2">*****</span>
                        <span id="balanceReal" class="ms-2 d-none"><?= number_format($user['balance'], 2, ',', '.') ?></span>
                        <button id="toggleBalance" type="button" class="btn btn-sm btn-outline-light rounded-circle ms-3">
                            <i id="toggleIcon" class="bi bi-eye-fill"></i>
                        </button>
                    </p>
                    <div class="mt-auto">
                        <span class="text-white-50 small fs-5">Nome: <?= esc(session()->get('name')) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards ou gráficos  -->
        <div class="col-md-7 mt-2 mb-3">
            <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp animate__delay-2s">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Histórico de Transações</h5>
                </div>
                <div class="card-body p-1">
                    <?php if (empty($transactions)): ?>
                        <div class="p-4 text-center text-muted">Nenhuma transação encontrada.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="transactionsTable" class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Descrição</th>
                                    <th>Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($transactions as $t): ?>
                                    <tr>
                                        <td><?= esc(ucfirst($t['type'])) ?></td>
                                        <td class="<?= $t['type'] === 'saída' || $t['type'] === 'reversão' ? 'text-danger' : 'text-success' ?>">
                                            R$ <?= number_format($t['amount'], 2, ',', '.') ?>
                                        </td>
                                        <td><?= esc($t['description']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>



    </div>
</div>

<?= view('layout/footer') ?>

