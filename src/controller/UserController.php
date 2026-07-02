<?php
    require_once __DIR__ . '/../service/LoginService.php';
    require_once __DIR__ . '/../service/NewsModifyService.php';

    // Controller class handles user actions (auth + news CRUD) and returns a unified response array.
    class UserController {
        private LoginService $loginService;
        private NewsModifyService $newsModifyService;

        public function __construct() {
            $this->loginService = new LoginService();
            $this->newsModifyService = new NewsModifyService();
        }

        public function onLoginClick(array $postData): array {
            $username = trim($postData['username'] ?? '');
            $password = $postData['password'] ?? '';

            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => __('_auth_missing_fields')];
            }

            $success = $this->loginService->login($username, $password);

            return [
                'success' => $success,
                'message' => $success
                    ? __('_auth_login_success', [':name' => htmlspecialchars($_SESSION['user']['name'])])
                    : __('_auth_login_failed'),
            ];
        }

        public function onLogoutClick(): array {
            $success = $this->loginService->logout();
            return [
                'success' => $success,
                'message' => __('_auth_logout_success'),
            ];
        }

        public function onLoadNews(): array {
            $news = $this->newsModifyService->getAllNews();
            return [
                'success' => true,
                'data'    => $news,
                'message' => __('_news_loaded'),
            ];
        }

        public function onCreateClick(array $postData): array {
            $title = $postData['title'] ?? '';
            $content = $postData['content'] ?? '';
            return $this->newsModifyService->createRecord($_SESSION['user'], $title, $content);
        }

        public function onEditClick(array $postData): array {
            $newsId = (int)($postData['newsId'] ?? 0);
            $title = $postData['title'] ?? '';
            $content = $postData['content'] ?? '';

            if ($newsId <= 0) {
                return [
                    'success' => false, 
                    'message' => __('_sys_invalid_article_id'),
                ];
            }

            return $this->newsModifyService->updateRecord($_SESSION['user'], $newsId, $title, $content);
        }

        public function onDeleteClick(array $postData): array {
            $newsId = (int)($postData['newsId'] ?? 0);

            if ($newsId <= 0) {
                return [
                    'success' => false, 
                    'message' => __('_sys_invalid_article_id'),
                ];
            }

            return $this->newsModifyService->deleteRecord($_SESSION['user'], $newsId);
        }
    }