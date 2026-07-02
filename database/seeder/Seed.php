<?php
    class Seed {
        private array $seedData;
        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
            $this->seedData = require __DIR__ . '/data.seed.php';
        }

        // The database requests seed data
        public function seed(): void {
            $this->createSeedStateTable();
            $this->seedTestUsers($this->seedData['initTestUsers']);
            $this->seedTestArticles($this->seedData['initTestArticles']);
        }

        private function createSeedStateTable(): void{
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS seed_state (
                    key TEXT PRIMARY KEY
                );
            ");
        }

        private function seedTestUsers(array $users): void{
            $stmt = $this->pdo->prepare("
                INSERT OR IGNORE INTO user (name, password, permission)
                VALUES (:name, :password, :permission)
            ");

            foreach ($users as $user) {
                $stmt->execute([
                    ':name'       => $user['name'],
                    ':password'   => $user['password'],
                    ':permission' => $user['permission'],
                ]);
            }
        }

        private function seedTestArticles(array $articles): void {
            // Seeded articles are uniquely marked with a key in a special table so that presentation data is generated only once at runtime
            $stmt = $this->pdo->prepare("SELECT 1 FROM seed_state WHERE key = 'dummy_data'");
            $stmt->execute();
            if ($stmt->fetchColumn()) return;

            $insert = $this->pdo->prepare("
                INSERT INTO news (user_id, title, content, created_at)
                VALUES (:user_id, :title, :content, datetime('now'))
            ");

            foreach ($articles as $article) {
                $insert->execute([
                    ':user_id' => 1,
                    ':title'   => $article['title'],
                    ':content' => $article['content'],
                ]);
            }
            
            $seeded = $this->pdo->prepare("INSERT INTO seed_state (key) VALUES ('dummy_data')");
            $seeded->execute();
        }
    }