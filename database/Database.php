<?php
    class Database {
        private PDO $pdo;
        private array $config;

        // Constructor loads the database setup from configuration and creates the database (scheme and configured data seeding)
        public function __construct() {
            $this->config = require __DIR__ . '/../config/app.config.php';
            require_once __DIR__ . '/seeder/Seed.php';

            try {
                $this->pdo = new PDO(
                    $this->config['dbConnection']['dsn'],
                    $this->config['dbConnection']['username'],
                    $this->config['dbConnection']['password'],
                    $this->config['dbConnection']['options']
                );
                $this->pdo->exec('PRAGMA foreign_keys = ON;');
                $this->createTables();

                // Configured data seeding
                if ($this->config['seedData']) (new Seed($this->pdo))->seed();
        
            } catch (\PDOException $e) {
                die('Database error: ' . $e->getMessage());
            }
        }

        // Database scheme for the application
        private function createTables(): void {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS user (
                    id         INTEGER PRIMARY KEY AUTOINCREMENT,
                    name       TEXT    NOT NULL UNIQUE,
                    password   TEXT    NOT NULL,
                    permission INTEGER NOT NULL DEFAULT 0
                );
            ");

            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS news (
                    id         INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id    INTEGER NOT NULL,
                    title      TEXT    NOT NULL,
                    content    TEXT    NOT NULL,
                    created_at TEXT    NOT NULL DEFAULT (datetime('now')),
                    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
                );
            ");
        }

        public function getConnection(): PDO {
            return $this->pdo;
        }
    }