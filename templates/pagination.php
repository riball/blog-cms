<?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Pages">
        <?php if ($page > 1): ?>
            <a href="<?= e(link_to($path, array_merge($params, ['page' => $page - 1]))) ?>">Newer</a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
        <span class="page-count">Page <?= (int) $page ?> of <?= (int) $totalPages ?></span>
        <?php if ($page < $totalPages): ?>
            <a href="<?= e(link_to($path, array_merge($params, ['page' => $page + 1]))) ?>">Older</a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
    </nav>
<?php endif; ?>