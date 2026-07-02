<?php
    require_once __DIR__ . '/../src/helper/Lang.php';
    $config = require_once __DIR__ . '/../config/app.config.php';

    if (session_status() === PHP_SESSION_NONE) session_start();

    $allowedLangs = ['en', 'de'];
    if (isset($_GET['lang']) && in_array($_GET['lang'], $allowedLangs, true)) {
        $_SESSION['lang'] = $_GET['lang'];
    }

    if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowedLangs, true)) {
        $lang = $_SESSION['lang'];
    } else {
        // First visit: detect from OS/browser Accept-Language header
        $lang = Lang::detectFromBrowser($allowedLangs);
        $_SESSION['lang'] = $lang;
    }

    // Get the dictionary for the currently set language
    Lang::init($lang);

    // POST: dispatch to controller, then exit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        ob_start();
        require_once __DIR__ . '/../src/dispatcher/ControllerDispatcher.php';
        $dispatcher = new ControllerDispatcher();
        $dispatcher->dispatch();
        exit;
    }

    // Pick the right view determined by session state
    $viewName = isset($_SESSION['user'])
        ? $config['viewRouting']['home']
        : $config['viewRouting']['login'];

    $viewPath = __DIR__ . '/../src/view/' . $viewName . '.php';
    $jsFile   = '/js/' . $viewName . '.js';

    // JS-safe locale: all keys without the '_' PHP-only prefix
    $jsLocaleJson = json_encode(Lang::jsLocale(), JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
    <head>
        <title><?= __('page_title') ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Hermann Schmidt">
        <meta name="description" content="My first full-stack project. A simple blog app with user authentication and CRUD operations for posts.">
        <meta name="robots" content="index, follow">
        <link rel="icon" type="image/svg+xml" href="/assets/icons/favicon-<?= htmlspecialchars($viewName) ?>.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css">

        <!-- i18n: locale injected by PHP -->
        <script>window.LOCALE = <?= $jsLocaleJson ?>;</script>
        <script src="/js/i18n.js"></script>
        <script src="<?= htmlspecialchars($jsFile) ?>" defer></script>
    </head>
    <body>
        <header>
            <nav class="lang-switcher" aria-label="<?= __('lang_switcher_label') ?>">
                <a href="?lang=en" class="lang-btn<?= $lang === 'en' ? ' lang-btn--active' : '' ?>" hreflang="en" title="<?= __('lang_title_en') ?>">EN</a>
                <a href="?lang=de" class="lang-btn<?= $lang === 'de' ? ' lang-btn--active' : '' ?>" hreflang="de" title="<?= __('lang_title_de') ?>">DE</a>
            </nav>
            <?php if (isset($_SESSION['user'])): ?>
            <button class="btn icon logout btn-header-logout" type="button" title="<?= __('logout_btn_title') ?>" aria-label="<?= __('logout_aria') ?>">
                <img src="/assets/icons/logout.svg" alt="<?= __('alt_logout') ?>">
            </button>
            <?php endif; ?>
        </header>

        <!-- View content determined by session state -->
        <?php include $viewPath; ?>
    </body>
</html>