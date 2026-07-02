<main>
    <p class="service-msg" role="status" aria-live="polite"></p>

     <button class="icon btn btn-create-toggle" id="create-toggle-btn" type="button" title="<?= __('create_toggle_title') ?>" style="display:none">
            <img src="/assets/icons/create.svg" alt="<?= __('alt_create') ?>">
    </button>

    <!-- Section entries -->
    <section class="newsboard">
        <div class="newsboard-header">
            <h2><?= __('news_heading') ?></h2>
        </div>
        <div id="news-container">
              <!-- News will be loaded by JS -->
            <p class="loading"><?= __('loading_news_text') ?></p>
        </div>
    </section>

    <!-- Section edit/create -->
    <section class="create-section" id="create-section" style="display:none">
        <div class="create-section-header">
            <h2 id="create-section-title"><?= __('create_title_label') ?></h2>
            <button class="icon btn" id="close-section-btn" type="button" aria-label="<?= __('close_panel_aria') ?>" title="<?= __('close_section_title') ?>">
                <img src="/assets/icons/cancel.svg" alt="<?= __('alt_close') ?>">
            </button>
        </div>
        <form class="create-form" novalidate>
            <input type="hidden" id="edit-news-id" value="">
            <input type="text" name="title" id="new-title"
                   placeholder="<?= __('ph_title') ?>"
                   title="<?= __('new_title_title') ?>"
                   required>
            <textarea name="content" id="new-content"
                   placeholder="<?= __('ph_content') ?>"
                   title="<?= __('new_content_title') ?>"
                   required></textarea>
            <div class="form-actions">
                <button class="btn" type="submit" id="create-submit-btn" title="<?= __('create_btn') ?>"><?= __('create_btn') ?></button>
            </div>
        </form>
    </section>
</main>

<!-- Entry modal (paper popup) -->
<div id="article-modal" class="article-modal-overlay" aria-modal="true" role="dialog" aria-labelledby="modal-title" style="display:none">
    <div class="article-modal-paper">
        <button class="article-modal-close icon btn" id="article-modal-close"
                aria-label="<?= __('modal_close_aria') ?>"
                title="<?= __('modal_close_title') ?>">
            <img src="/assets/icons/cancel.svg" alt="<?= __('alt_close') ?>">
        </button>
        <div class="article-modal-content">
            <h2 class="article-modal-title" id="modal-title"></h2>
            <p class="article-modal-author"></p>
            <div class="article-modal-body"></div>
        </div>
    </div>
</div>