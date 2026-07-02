<?php
    require_once __DIR__ . '/../constants/Permission.php';
    require_once __DIR__ . '/../repository/BaseRepo.php';

    // Class handles business logic for news — validates permissions and input before calling the repo.
    class NewsModifyService {
        private BaseRepo $baseRepo;

        public function __construct() {
            $this->baseRepo = new BaseRepo();
        }

        public function getAllNews(): array {
            return $this->baseRepo->readAllNews();
        }

        public function createRecord(array $user, string $title, string $content): array {
            if (!($user['permission'] & Permission::WRITE)) {
                return ['success' => false, 'message' => __('_news_no_permission_create')];
            }

            $title = trim($title);
            $content = trim($content);

            if (empty($title) || empty($content)) {
                return ['success' => false, 'message' => __('_news_empty_fields')];
            }

            $success = $this->baseRepo->createNews($user['id'], $title, $content);
            return [
                'success' => $success,
                'message' => $success ? __('_news_created') : __('_news_create_failed'),
            ];
        }

        public function updateRecord(array $user, int $newsId, string $title, string $content): array {
            if (!($user['permission'] & Permission::UPDATE)) {
                return ['success' => false, 'message' => __('_news_no_permission_edit')];
            }

            $title = trim($title);
            $content = trim($content);

            if (empty($title) || empty($content)) {
                return ['success' => false, 'message' => __('_news_empty_fields')];
            }

            $success = $this->baseRepo->updateNews($newsId, $user['id'], $title, $content);
            return [
                'success' => $success,
                'message' => $success ? __('_news_updated') : __('_news_update_failed'),
            ];
        }

        public function deleteRecord(array $user, int $newsId): array {
            if (!($user['permission'] & Permission::DELETE)) {
                return ['success' => false, 'message' => __('_news_no_permission_delete')];
            }

            $success = $this->baseRepo->deleteNews($newsId, $user['id']);
            return [
                'success' => $success,
                'message' => $success ? __('_news_deleted') : __('_news_delete_failed'),
            ];
        }
    }