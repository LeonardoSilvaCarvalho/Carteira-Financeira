<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(1);
$links = $pager->links();
?>

<?php if (!empty($links)): ?>
    <nav aria-label="<?= lang('Pager.pageNavigation') ?>">
        <ul class="pagination justify-content-center">

            <!-- Página Anterior -->
            <?php
            $prev = null;
            foreach ($links as $i => $link) {
                if ($link['active'] && $i > 0) {
                    $prev = $links[$i - 1]['uri'];
                    break;
                }
            }
            ?>
            <li class="page-item <?= $prev ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $prev ?? '#' ?>" aria-label="<?= lang('Pager.previous') ?>">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Links numerados -->
            <?php foreach ($links as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Próxima Página -->
            <?php
            $next = null;
            foreach ($links as $i => $link) {
                if ($link['active'] && $i < count($links) - 1) {
                    $next = $links[$i + 1]['uri'];
                    break;
                }
            }
            ?>
            <li class="page-item <?= $next ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $next ?? '#' ?>" aria-label="<?= lang('Pager.next') ?>">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>

        </ul>
    </nav>
<?php endif; ?>
