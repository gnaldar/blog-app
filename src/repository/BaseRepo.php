<?php
    require_once __DIR__ . '/../../database/Database.php';

    // Class handles all direct database CRUD queries.
    class BaseRepo {
        private PDO $pdo;

        public function __construct() {
            $db = new Database();
            $this->pdo = $db->getConnection();
        }

        public function findUserByCredentials(string $username, string $password): array|false {
            try {
                $stmt = $this->pdo->prepare('SELECT * FROM user WHERE name = :name');
                $stmt->execute([':name' => $username]);
                $user = $stmt->fetch();
                if ($user && password_verify($password, $user['password'])) {
                    return $user;
                }
                return false;

            } catch (\PDOException $e) {
                error_log('BaseRepo::findUserByCredentials – ' . $e->getMessage());
                return false;
            }
        }

        public function createNews(int $userId, string $title, string $content): bool {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO news (user_id, title, content, created_at)
                    VALUES (:user_id, :title, :content, datetime('now'))
                ");
                return $stmt->execute([
                    ':user_id' => $userId,
                    ':title'   => $title,
                    ':content' => $content,
                ]);
                
            } catch (\PDOException $e) {
                error_log('BaseRepo::createNews – ' . $e->getMessage());
                return false;
            }
        }

        public function readAllNews(): array {
            try {
                $stmt = $this->pdo->query('
                    SELECT news.id, news.title, news.content, news.created_at, user.name AS author
                    FROM news
                    JOIN user ON news.user_id = user.id
                    ORDER BY news.id DESC
                ');
                return $stmt->fetchAll();

            } catch (\PDOException $e) {
                error_log('BaseRepo::readAllNews – ' . $e->getMessage());
                return [];
            }
        }

        public function updateNews(int $newsId, int $userId, string $title, string $content): bool {
            try {
                $stmt = $this->pdo->prepare('
                    UPDATE news
                    SET title = :title, content = :content
                    WHERE id = :id AND user_id = :user_id
                ');
                return $stmt->execute([
                    ':id'      => $newsId,
                    ':user_id' => $userId,
                    ':title'   => $title,
                    ':content' => $content,
                ]);

            } catch (\PDOException $e) {
                error_log('BaseRepo::updateNews – ' . $e->getMessage());
                return false;
            }
        }

        public function deleteNews(int $newsId, int $userId): bool {
            try {
                $stmt = $this->pdo->prepare('
                    DELETE FROM news
                    WHERE id = :id AND user_id = :user_id
                ');
                return $stmt->execute([
                    ':id'      => $newsId,
                    ':user_id' => $userId,
                ]);

            } catch (\PDOException $e) {
                error_log('BaseRepo::deleteNews – ' . $e->getMessage());
                return false;
            }
        }
    }