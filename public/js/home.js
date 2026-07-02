"use strict";

// Raw server data kept separate – DOM inputs strip line-breaks from values
let articlesCache = {};

// Helper functions
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function turnLightOn(el, on) {
    if (el) el.classList.toggle('btn-activated', on);
}

// Converts SQLite "YYYY-MM-DD HH:MM:SS" to MM/DD/YYYY (en) or DD.MM.YYYY (de)
function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr.replace(' ', 'T'));
    if (isNaN(d.getTime())) return dateStr;
    const dd   = String(d.getDate()).padStart(2, '0');
    const mm   = String(d.getMonth() + 1).padStart(2, '0');
    const yyyy = d.getFullYear();
    const lang = document.documentElement.lang || 'en';

    if (lang === 'en') {
        return `${mm}/${dd}/${yyyy}`;
    }

    return `${dd}.${mm}.${yyyy}`;
}

async function postToServer(data) {
    if (!(data instanceof FormData)) {
        const fd = new FormData();
        for (const [key, value] of Object.entries(data)) fd.append(key, value);
        data = fd;
    }

    const response = await fetch('/index.php', { method: 'POST', body: data });

    if (!response.ok) throw new Error(t('http_error') + response.status);

    return response.json();
}

function showMessage(text, isError = false) {
    const msgEl       = document.querySelector('.service-msg');
    msgEl.textContent = text;
    msgEl.classList.remove('service-msg--success', 'service-msg--error');
    msgEl.classList.add(isError ? 'service-msg--error' : 'service-msg--success');
    setTimeout(() => { msgEl.textContent = ''; msgEl.classList.remove('service-msg--success', 'service-msg--error'); }, 4000);
}

function clearEditActivation() {
    document.querySelectorAll('.icon.edit').forEach(e => turnLightOn(e, false));
}

function setEditActivation(editBtn) {
    clearEditActivation();
    turnLightOn(editBtn, true);
}

function setCreateActivationState(active) {
    turnLightOn(document.getElementById('create-toggle-btn'), active);
}

// News loading and rendering
async function loadNews() {
    const container = document.getElementById('news-container');
    container.innerHTML = `<p class="loading">${t('loading_articles')}</p>`;

    try {
        const data = await postToServer({ action: 'loadNews' });

        if (!data.success) {
            container.innerHTML = `<p>${t('load_error')}</p>`;
            return;
        }

        renderNews(data.data);

    } catch (err) {
        container.innerHTML = `<p>${t('connection_error')}${err.message}</p>`;
    }
}

function renderNews(articles) {
    const container = document.getElementById('news-container');

    if (!articles || articles.length === 0) {
        container.innerHTML = `<p>${t('no_articles')}</p>`;
        articlesCache = {};
        showCreateSection(false);
        resetEditFields();
        updateCloseBtnVisibility();
        return;
    }

    articlesCache = {};
    articles.forEach(a => { articlesCache[a.id] = a; });

    container.innerHTML = articles.map(article => `
        <div class="entry" data-id="${article.id}" title="${t('entry_hover_title')} ${formatDate(article.created_at)}">
            <div class="entry-content">
                <input
                    type="text"
                    name="entry-title"
                    value="${escapeHtml(article.title)}"
                    disabled
                    title="${t('article_title') || 'Article title'}"
                    aria-label="Title: ${escapeHtml(article.title)}"
                >
                <input
                    type="text"
                    name="entry-description"
                    value="${escapeHtml(article.content)}"
                    disabled
                    title="${t('article_content') || 'Article content'}"
                    aria-label="Content"
                >
                <span class="entry-author">
                    ${t('by_prefix')} <span class="author-name">${escapeHtml(article.author)}</span>,
                    ${t('authored_on')} ${formatDate(article.created_at)}
                </span>
            </div>
            <div class="entry-actions">
                <button class="icon btn edit"
                        aria-label="${t('edit_aria')}"
                        title="${t('edit_btn_title')}">
                    <img src="/assets/icons/edit.svg" alt="${t('edit_alt')}">
                </button>
                <button class="icon btn delete"
                        aria-label="${t('delete_aria')}"
                        title="${t('delete_btn_title')}">
                    <img src="/assets/icons/delete.svg" alt="${t('delete_alt')}">
                </button>
            </div>
        </div>
    `).join('');

    const editId = document.getElementById('edit-news-id').value;
    if (!editId) {
        document.getElementById('create-section').style.display = 'none';
    }

    updateCloseBtnVisibility();
}

// CSS-Animation 
function flashElement(el) {
    el.classList.remove('flash-update');
    void el.offsetWidth; // forces reflow so the animation restarts cleanly
    el.classList.add('flash-update');
    el.addEventListener('animationend', () => el.classList.remove('flash-update'), { once: true });
}

// Clears form fields and resets labels – does not touch panel visibility
function resetEditFields() {
    document.getElementById('edit-news-id').value               = '';
    document.getElementById('new-title').value                  = '';
    document.getElementById('new-content').value                = '';
    document.getElementById('create-section-title').textContent = t('create_title_label');
    document.getElementById('create-submit-btn').textContent    = t('create_btn');
    clearEditActivation();
}

// '+'-button and 'x'-button are only relevant when articles exist
function updateCloseBtnVisibility() {
    const hasArticles = !!document.querySelector('#news-container .entry');
    document.getElementById('close-section-btn').style.display  = hasArticles ? 'inline-flex' : 'none';
    document.getElementById('create-toggle-btn').style.display  = hasArticles ? 'flex'        : 'none';
}

function enterEditMode(entryEl, editBtn) {
    const id      = entryEl.dataset.id;
    const cached  = articlesCache[id];
    const title   = cached ? cached.title   : entryEl.querySelector('input[name="entry-title"]').value;
    const content = cached ? cached.content : entryEl.querySelector('input[name="entry-description"]').value;

    document.getElementById('edit-news-id').value               = id;
    document.getElementById('new-title').value                  = title;
    document.getElementById('new-content').value                = content;
    document.getElementById('create-section-title').textContent = t('edit_title_label');
    document.getElementById('create-submit-btn').textContent    = t('save_btn');

    const createSection = document.querySelector('.create-section');
    showCreateSection(false);
    setCreateActivationState(false);
    setEditActivation(editBtn);
    createSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(() => flashElement(createSection), 300);
}

function exitEditMode() {
    resetEditFields();
    hideCreateSectionIfHasArticles();
}

// CRUD-Handlers
function showConfirm(message, confirmLabel, variant = 'neutral', okTitle = confirmLabel) {
    return new Promise(resolve => {
        const overlay = document.createElement('div');
        overlay.className = 'confirm-modal-overlay';
        overlay.innerHTML = `
            <div class="confirm-modal-paper${variant === 'danger' ? ' confirm-modal-paper--danger' : ''}">
                <p class="confirm-modal-msg">${message}</p>
                <div class="confirm-modal-actions">
                    <button class="btn confirm-cancel-btn" title="${t('confirm_cancel')}">${t('confirm_cancel')}</button>
                    <button class="btn confirm-ok-btn confirm-ok--${variant}" title="${okTitle}">${confirmLabel}</button>
                </div>
            </div>`;
        document.body.appendChild(overlay);
        const close = result => { overlay.remove(); resolve(result); document.removeEventListener('keydown', onKey); };
        const onKey = e => { if (e.key === 'Escape') close(false); };
        overlay.querySelector('.confirm-cancel-btn').addEventListener('click', () => close(false));
        overlay.querySelector('.confirm-ok-btn').addEventListener('click',    () => close(true));
        overlay.addEventListener('click', e => { if (e.target === overlay) close(false); });
        document.addEventListener('keydown', onKey);
        overlay.querySelector('.confirm-cancel-btn').focus();
    });
}

async function deleteHandler(entryEl, deleteBtn) {
    turnLightOn(deleteBtn, true);
    if (!await showConfirm(t('delete_confirm'), t('confirm_delete'), 'danger', t('delete_aria'))) {
        turnLightOn(deleteBtn, false);
        return;
    }
    turnLightOn(deleteBtn, false);

    const newsId         = entryEl.dataset.id;
    const editId         = document.getElementById('edit-news-id').value;
    const wasEditingThis = editId === newsId;

    try {
        const data = await postToServer({ action: 'delete', newsId });

        if (data.success) {
            showMessage(data.message);
            entryEl.remove();
            const hasArticles = !!document.querySelector('#news-container .entry');

            if (!hasArticles) {
                resetEditFields();
                showCreateSection(false);
            } else if (wasEditingThis) {
                exitEditMode();
            }
            updateCloseBtnVisibility();
        } else {
            showMessage(data.message, true);
        }
    } catch (err) {
        showMessage(t('connection_error') + err.message, true);
    }
}

async function createHandler(event) {
    event.preventDefault();

    const editId  = document.getElementById('edit-news-id').value;
    const title   = document.getElementById('new-title').value.trim();
    const content = document.getElementById('new-content').value.trim();

    if (!title || !content) {
        showMessage(t('fields_required'), true);
        return;
    }

    try {
        const data = editId
            ? await postToServer({ action: 'edit', newsId: editId, title, content })
            : await postToServer({ action: 'create', title, content });

        if (data.success) {
            showMessage(data.message);
            const targetId    = editId;
            exitEditMode();
            await loadNews();
            const selector    = targetId
                ? `.entry[data-id="${targetId}"]`
                : '#news-container .entry:first-child';
            const targetEntry = document.querySelector(selector);
            if (targetEntry) {
                hideCreateSectionIfHasArticles();
                targetEntry.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => flashElement(targetEntry), 300);
            }
        } else {
            showMessage(data.message, true);
        }
    } catch (err) {
        showMessage(t('connection_error') + err.message, true);
    }
}

// Article Modal
function openArticleModal(article) {
    const overlay  = document.getElementById('article-modal');
    const authorEl = overlay.querySelector('.article-modal-author');

    overlay.querySelector('.article-modal-title').textContent = article.title;
    authorEl.innerHTML =
        `${t('by_prefix')} <span class="author-name">${escapeHtml(article.author)}</span>, ` +
        `${t('authored_on')} ${formatDate(article.created_at)}`;
    overlay.querySelector('.article-modal-body').textContent = article.content;
    overlay.style.display        = 'flex';
    document.body.style.overflow = 'hidden';
    overlay.querySelector('.article-modal-close').focus();
}

function closeArticleModal() {
    const overlay = document.getElementById('article-modal');
    overlay.style.display        = 'none';
    document.body.style.overflow = '';
}

// Logout
async function logoutHandler(event) {
    event.preventDefault();
    const btn = event.currentTarget;
    turnLightOn(btn, true);
    if (!await showConfirm(t('logout_confirm'), t('confirm_logout'), 'neutral', t('confirm_logout'))) {
        turnLightOn(btn, false);
        return;
    }

    try {
        const data = await postToServer({ action: 'logout' });

        if (data.success) {
            // sessionStorage bridges the logout message across the page reload to login.js
            sessionStorage.setItem('postLogoutMsg', t('logout_success_msg'));
            window.location.reload();
        } else {
            showMessage(data.message, true);
        }
    } catch (err) {
        showMessage(t('connection_error') + err.message, true);
    }
}

// Section visibility helpers 
function showCreateSection(scroll = true) {
    const sec = document.getElementById('create-section');
    sec.style.display = '';
    updateCloseBtnVisibility();
    setCreateActivationState(true);
    if (!document.getElementById('edit-news-id').value) {
        clearEditActivation();
    }
    if (scroll) {
        sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setTimeout(() => flashElement(sec), 300);
    }
}

function hideCreateSectionIfHasArticles() {
    const hasArticles = !!document.querySelector('#news-container .entry');
    if (hasArticles) {
        document.getElementById('create-section').style.display = 'none';
        setCreateActivationState(false);
        clearEditActivation();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('create-toggle-btn').title = t('create_toggle_title');
    document.getElementById('close-section-btn').title = t('close_section_title');
    document.getElementById('new-title').title         = t('new_title_title');
    document.getElementById('new-content').title       = t('new_content_title');
    document.querySelector('.logout').title            = t('logout_btn_title');

    loadNews();

    document.querySelector('.create-form').addEventListener('submit', createHandler);
    document.querySelector('.logout').addEventListener('click', logoutHandler);

    document.getElementById('close-section-btn').addEventListener('click', () => {
        exitEditMode();
        document.getElementById('create-section').style.display = 'none';
    });

    document.getElementById('create-toggle-btn').addEventListener('click', () => {
        const inEditMode = !!document.getElementById('edit-news-id').value;

        if (inEditMode) {
            // Switch from edit → create without closing the panel
            resetEditFields();
            setCreateActivationState(true);
            updateCloseBtnVisibility();
            setTimeout(() => flashElement(document.getElementById('create-section')), 300);
            return;
        }

        const sec = document.getElementById('create-section');
        if (sec.style.display === 'none' || sec.style.display === '') {
            showCreateSection();
        }
        // Panel already open in create mode: do nothing (one-way toggle)
    });

    // Delegated clicks for edit, delete, and article-open on the news list
    document.getElementById('news-container').addEventListener('click', event => {
        const editBtn   = event.target.closest('.icon.edit');
        const deleteBtn = event.target.closest('.icon.delete');
        const entryEl   = event.target.closest('.entry');
        if (!entryEl) return;

        if (editBtn)   { enterEditMode(entryEl, editBtn); return; }
        if (deleteBtn) { deleteHandler(entryEl, deleteBtn).catch(err => showMessage(t('connection_error') + err.message, true)); return; }


        // Fall back to DOM values if the entry was rendered before cache was populated
        const cached  = articlesCache[entryEl.dataset.id];
        const article = cached ?? {
            title:      entryEl.querySelector('input[name="entry-title"]').value,
            content:    entryEl.querySelector('input[name="entry-description"]').value,
            author:     entryEl.querySelector('.author-name')?.textContent ?? '',
            created_at: '',
        };
        openArticleModal(article);
    });

    document.getElementById('article-modal-close').addEventListener('click', closeArticleModal);
    document.getElementById('article-modal').addEventListener('click', event => {
        if (event.target === event.currentTarget) closeArticleModal();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeArticleModal();
    });
});