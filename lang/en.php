<?php
    return [
        /* ✦-✦-✦-✦-✦-✦-✦ Loading / errors (JS) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'loading_articles'        => 'Loading articles…',
        'load_error'              => 'Error loading articles.',
        'connection_error'        => 'Connection error: ',
        'http_error'              => 'HTTP Error: ',
        'server_error'            => 'Server error: HTTP ',

        /* ✦-✦-✦-✦-✦-✦-✦ News list (JS) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'no_articles'             => 'No articles yet.',
        'by_prefix'               => 'by',
        'authored_on'             => 'authored on',
        'entry_hover_title'       => 'Entry from',

        /* ✦-✦-✦-✦-✦-✦-✦ Entry actions (JS) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'delete_confirm'          => 'Really delete this article?',
        'logout_confirm'          => 'Really sign out?',
        'confirm_cancel'          => 'Cancel',
        'confirm_delete'          => 'Delete',
        'confirm_logout'          => 'Sign out',
        'edit_aria'               => 'Edit article',
        'delete_aria'             => 'Delete article',
        'edit_alt'                => 'Edit',
        'delete_alt'              => 'Delete',

        /* ✦-✦-✦-✦-✦-✦-✦ Create / edit form (JS + view) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'create_title_label'      => 'Create new article',
        'edit_title_label'        => 'Edit article',
        'create_btn'              => 'Create',
        'save_btn'                => 'Save',
        'fields_required'         => 'Title and content are required.',
        'ph_title'                => 'Title',
        'ph_content'              => 'Content',
        'article_title'           => 'Article headline',
        'article_content'         => 'Article body text',

        /* ✦-✦-✦-✦-✦-✦-✦ Auth (JS) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'logging_in'              => 'Logging in…',
        'logout_success_msg'      => 'You have been successfully logged out.',

        /* ✦-✦-✦-✦-✦-✦-✦ Tooltips – title attribute (JS + view) ✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'lang_title_en'           => 'English language',
        'lang_title_de'           => 'German language',
        'username_title'          => 'Enter your username',
        'password_title'          => 'Enter your password',
        'login_btn_title'         => 'Sign in',
        'logout_btn_title'        => 'Sign out',
        'create_toggle_title'     => 'Create new article',
        'close_section_title'     => 'Close panel',
        'new_title_title'         => 'Article headline',
        'new_content_title'       => 'Article body text',
        'edit_btn_title'          => 'Edit this article',
        'delete_btn_title'        => 'Delete this article',
        'modal_close_title'       => 'Close',

        /* ✦-✦-✦-✦-✦-✦-✦ Alt text (JS + view) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'alt_login'               => 'Login',
        'alt_create'              => 'Create',
        'alt_close'               => 'Close',
        'alt_logout'              => 'Logout',

        /* ✦-✦-✦-✦-✦-✦-✦ Placeholder text (view) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'ph_username'             => 'Username',
        'ph_password'             => 'Password',

        /* ✦-✦-✦-✦-✦-✦-✦ Aria labels (JS + view) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'modal_close_aria'        => 'Close',
        'logout_aria'             => 'Sign out',
        'close_panel_aria'        => 'Close panel',

        /* ✦-✦-✦-✦-✦-✦-✦ View static text (view only) ✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦-✦ */
        'page_title'              => 'Full-Stack Project – Blogsite',
        'lang_switcher_label'     => 'Language',
        'news_heading'            => 'All News',
        'loading_news_text'       => 'Loading news…',

        /* ✦-✦-✦-✦-✦-✦-✦ API response messages (PHP only, NOT sent to JS) ✦-✦-✦-✦-✦ */
        '_auth_login_success'     => 'Login successful. Welcome, :name!',
        '_auth_login_failed'      => 'Invalid username or password.',
        '_auth_missing_fields'    => 'Username and password are required.',
        '_auth_logout_success'    => 'Successfully logged out.',
        '_auth_not_logged_in'     => 'Not logged in.',

        '_news_loaded'            => 'News loaded.',
        '_news_created'           => 'Article created.',
        '_news_create_failed'     => 'Failed to create article.',
        '_news_updated'           => 'Article updated.',
        '_news_update_failed'     => 'Failed to update article.',
        '_news_deleted'           => 'Article deleted.',
        '_news_delete_failed'     => 'Failed to delete article.',
        '_news_empty_fields'      => 'Title and content must not be empty.',
        '_news_no_permission'     => 'No permission.',
        '_news_no_permission_create' => 'No permission to create articles.',
        '_news_no_permission_edit'   => 'No permission to edit articles.',
        '_news_no_permission_delete' => 'No permission to delete articles.',

        '_sys_invalid_article_id' => 'Invalid article ID.',
        '_sys_unknown_action'     => 'Unknown action: :action',
        '_sys_internal_error'     => 'Internal server error.',
    ];