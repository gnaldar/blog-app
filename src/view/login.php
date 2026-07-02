<main>
    <p class="service-msg" role="status" aria-live="polite"></p>

    <!-- Section login -->
    <form class="form login-form" novalidate>
        <input type="text" name="username" id="username"
               placeholder="<?= __('ph_username') ?>"
               autocomplete="username"
               title="<?= __('username_title') ?>"
               required>
        <input type="password" name="password" id="password"
               placeholder="<?= __('ph_password') ?>"
               autocomplete="current-password"
               title="<?= __('password_title') ?>"
               required>
        <button type="submit" id="loginBtn" class="icon btn btn-login" title="<?= __('login_btn_title') ?>">
            <img src="/assets/icons/login.svg" alt="<?= __('alt_login') ?>">
        </button>
    </form>
</main>