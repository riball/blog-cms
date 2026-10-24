<?php
$siteName = config()['site_name'];
$pageTitle = $pageTitle ?? $siteName;
$metaDescription = $metaDescription ?? '';
$canonical = $canonical ?? null;
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <?php if ($metaDescription !== ''): ?>
        <meta name="description" content="<?= e($metaDescription) ?>">
    <?php endif; ?>
    <?php if ($canonical): ?>
        <link rel="canonical" href="<?= e($canonical) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&display=swap">
    <link rel="stylesheet" href="<?= e(url('css/style.css')) ?>">
</head>
<body class="<?= e($bodyClass) ?>">
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('index.php')) ?>"><?= e($siteName) ?></a>
        <form class="search" action="<?= e(url('search.php')) ?>" method="get" role="search">
            <input type="search" name="q" maxlength="100" placeholder="Search posts" aria-label="Search posts">
        </form>
    </div>
</header>
<main class="container">