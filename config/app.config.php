<?php
    return [
        // Database configuration (SQLite setup)
        'dbConnection' => [
            'dsn'      => 'sqlite:' . __DIR__ . '/../database/news.sqlite',
            'username' => null,
            'password' => null,
            'options'  => [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ],
        ],

        // View routing for index.php
        'viewRouting' => [
            'home'  => 'home',
            'login' => 'login',
        ],

        // Initialize dummy data
        'seedData' => true,
    ];