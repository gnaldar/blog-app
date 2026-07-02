"use strict";

function setLoginMsg(el, text, isError) {
    el.textContent = text;
    el.classList.remove('service-msg--success', 'service-msg--error');
    el.classList.add(isError ? 'service-msg--error' : 'service-msg--success');
}

function loginHandler(event) {
    event.preventDefault();

    const form      = document.querySelector('.login-form');
    const msgEl     = document.querySelector('.service-msg');
    const submitBtn = document.getElementById('loginBtn');
    const formData  = new FormData(form);

    formData.append('action', 'login');
    submitBtn.disabled = true;
    msgEl.textContent  = t('logging_in');

    fetch('/index.php', { method: 'POST', body: formData })
        .then(response => {
            if (!response.ok) throw new Error(t('server_error') + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                setLoginMsg(msgEl, data.message, false); submitBtn.classList.add('login-success');
                setTimeout(() => window.location.reload(), 850);
            } else {
                setLoginMsg(msgEl, data.message, true);
                document.getElementById('password').value = '';
                submitBtn.classList.add('login-error'); setTimeout(()=>submitBtn.classList.remove('login-error'),1200); submitBtn.disabled=false;
            }
        })
        .catch(error => {
            setLoginMsg(msgEl, t('connection_error') + error.message, true);
            submitBtn.classList.add('login-error'); setTimeout(()=>submitBtn.classList.remove('login-error'),1200); submitBtn.disabled = false;
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loginBtn      = document.getElementById('loginBtn');

    if (usernameInput) usernameInput.title = t('username_title');
    if (passwordInput) passwordInput.title = t('password_title');
    if (loginBtn)      loginBtn.title      = t('login_btn_title');

    const logoutMsg = sessionStorage.getItem('postLogoutMsg');
    if (logoutMsg) {
        const msgEl = document.querySelector('.service-msg');
        if (msgEl) setLoginMsg(msgEl, logoutMsg, false);
        sessionStorage.removeItem('postLogoutMsg');
    }

    document.querySelector('.login-form').addEventListener('submit', loginHandler);
});