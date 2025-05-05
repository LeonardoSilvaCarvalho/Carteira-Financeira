<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Carteira Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!--  Auth CSS  -->
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body>
<div class="container">
    <div class="card p-4" style="max-width: 420px; margin: auto;">
        <h3 class="text-center mb-4 text-primary">Entrar na Carteira</h3>

        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-warning text-center">
                <?= esc(session()->getFlashdata('msg')) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('/loginAuth') ?>">
            <div class="mb-3">
                <input name="agency" type="text" class="form-control" placeholder="Agência"
                       value="<?= isset($_COOKIE['agency_number']) ? esc($_COOKIE['agency_number']) : '' ?>" required>
            </div>

            <div class="mb-3 position-relative">
                <?php
                $fullAccount = $_COOKIE['account_number'] ?? '';
                $maskedAccount = $fullAccount ? substr($fullAccount, 0, 3) . '****' : '';
                ?>
                <input id="account_display" type="text"
                       class="form-control"
                       placeholder="Conta"
                       value="<?= esc($maskedAccount) ?>"
                    <?= $fullAccount ? 'readonly' : '' ?>
                       required>

                <input type="hidden" name="account" id="account" value="<?= esc($fullAccount) ?>">

                <?php if ($fullAccount): ?>
                    <button type="button" id="toggleAccountBtn" class="toggle-btn" onclick="toggleAccount()">
                        <i class="bi bi-eye"></i>
                    </button>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <input name="cpf" type="text" class="form-control" placeholder="CPF" required>
            </div>

            <div class="mb-3">
                <input name="password" type="password" class="form-control" placeholder="Senha" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small>Não tem conta? <a href="<?= base_url('/register') ?>" class="text-decoration-none text-primary">Cadastre-se</a></small>
        </div>
    </div>
</div>


<script>
    let showingFull = false;
    const fullAccount = "<?= esc($fullAccount) ?>";
    const maskedAccount = fullAccount.substring(0, 3) + '****';

    function toggleAccount() {
        const displayInput = document.getElementById('account_display');
        const hiddenInput = document.getElementById('account');
        const buttonIcon = document.getElementById('toggleAccountBtn').querySelector('i');

        if (!showingFull) {
            displayInput.value = fullAccount;
            displayInput.removeAttribute('readonly');
            buttonIcon.classList.replace('bi-eye', 'bi-eye-slash');
            showingFull = true;
        } else {
            displayInput.value = maskedAccount;
            displayInput.setAttribute('readonly', true);
            buttonIcon.classList.replace('bi-eye-slash', 'bi-eye');
            showingFull = false;
        }
    }

    document.getElementById('account_display')?.addEventListener('input', function () {
        document.getElementById('account').value = this.value;
    });
</script>

</body>
</html>
