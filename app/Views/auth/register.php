<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Carteira Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--  Auth CSS  -->
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body>

<div class="container">
    <div class="card p-4" style="max-width: 420px; margin: auto;">
        <h3 class="text-center mb-4 text-primary">Criar Conta</h3>

        <form method="POST" action="/save">
            <div class="mb-3">
                <input name="name" type="text" class="form-control" placeholder="Nome completo" value="<?= set_value('name'); ?>" required>
                <?php if (isset($validation)) : ?>
                    <div class="form-text"><?= $validation->getError('name') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <input name="email" type="email" class="form-control" placeholder="E-mail" value="<?= set_value('email'); ?>" required>
                <?php if (isset($validation)) : ?>
                    <div class="form-text"><?= $validation->getError('email') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <input name="cpf" type="text" id="cpf" class="form-control" placeholder="CPF" value="<?= set_value('cpf'); ?>" required>
                <?php if (isset($validation)) : ?>
                    <div class="form-text"><?= $validation->getError('cpf') ?></div>
                <?php elseif (isset($cpf_error)) : ?>
                    <div class="form-text"><?= $cpf_error ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <input name="password" type="password" class="form-control" placeholder="Senha" required>
                <?php if (isset($validation)) : ?>
                    <div class="form-text"><?= $validation->getError('password') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small>Já tem conta? <a href="<?= base_url('/') ?>" class="text-decoration-none text-primary">Entrar</a></small>
        </div>
    </div>
</div>

<script>
    document.getElementById('cpf')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 3) {
            e.target.value = value;
        } else if (value.length <= 6) {
            e.target.value = value.replace(/(\d{3})(\d{1,})/, '$1.$2');
        } else if (value.length <= 9) {
            e.target.value = value.replace(/(\d{3})(\d{3})(\d{1,})/, '$1.$2.$3');
        } else {
            e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1})/, '$1.$2.$3-$4');
        }
    });
</script>

<?php if (isset($swal) && $swal): ?>
    <script>
        Swal.fire({
            title: 'Cadastro realizado com sucesso!',
            html: `
            <p><strong>Nome:</strong> <?= esc($name) ?></p>
            <p><strong>Agência:</strong> <?= esc($agency_number) ?></p>
            <p><strong>Conta:</strong> <?= esc($account_number) ?></p>
            <p><strong>Atenção:</strong> Guarde esses dados, pois serão necessários para o login.</p>
            <p>Você será redirecionado para a tela de login em <span id="countdown">60</span> segundos.</p>
        `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                let countdown = 60;
                const interval = setInterval(() => {
                    countdown--;
                    document.getElementById('countdown').textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        Swal.fire({
                            icon: 'success',
                            title: 'Redirecionando...',
                            text: 'Você será levado para a tela de login.',
                        }).then(() => {
                            window.location.href = "<?= base_url('/') ?>";
                        });
                    }
                }, 1000);
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
