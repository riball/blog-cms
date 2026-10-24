<?php if (!$posts): ?>
    <p class="empty">No posts found.</p>
<?php else: ?>
    <ul class="post-list">
        <?php foreach ($posts as $post): ?>
            <li class="post-item">
                <time class="post-date" datetime="<?= e(substr($post['published_at'], 0, 10)) ?>">
                    <?= e(format_date($post['published_at'])) ?>
                </time>
                <div class="post-main">
                    <h2 class="post-title">
                        <a href="<?= e(link_to('post.php', ['slug' => $post['slug']])) ?>"><?= e($post['title']) ?></a>
                    </h2>
                    <?php if (!empty($post['excerpt'])): ?>
                        <p class="post-excerpt"><?= e($post['excerpt']) ?></p>
                    <?php endif; ?>
                    <?php if ($post['category_name']): ?>
                        <a class="tag" href="<?= e(link_to('category.php', ['slug' => $post['category_slug']])) ?>"><?= e($post['category_name']) ?></a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>