</main>
<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> <?= e(config()['site_name']) ?></p>
    </div>
</footer>
<?php if (!empty($pageScript)): ?>
    <script src="<?= e(url($pageScript)) ?>"></script>
<?php endif; ?>
</body>
</html>
