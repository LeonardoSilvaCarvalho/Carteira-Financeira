<?= view('layout/header', ['title' => 'Transferência Bancária']) ?>
<?= view('layout/sidebar') ?>

<div class="col-md-10 p-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body">
                        <?= view('partials/reverse_table', ['transactions' => $transactions]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>
