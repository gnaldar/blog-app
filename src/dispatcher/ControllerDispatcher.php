<?php
    require_once __DIR__ . '/../controller/UserController.php';

    // Dispatcher class handles request routing and controller delegation.
    class ControllerDispatcher {
        private UserController $controller;

        public function __construct() {
            $this->controller = new UserController();
        }

        public function dispatch(): void {
            $action = $_POST['action'] ?? null;
            $publicActions = ['login'];
            $requiresSession = !in_array($action, $publicActions);

            if ($requiresSession && !isset($_SESSION['user'])) {
                $this->sendJson(['success' => false, 'message' => __('_auth_not_logged_in')]);
                return;
            }

            try {
                $response = match($action) {
                    'login'    => $this->controller->onLoginClick($_POST),
                    'logout'   => $this->controller->onLogoutClick(),
                    'loadNews' => $this->controller->onLoadNews(),
                    'create'   => $this->controller->onCreateClick($_POST),
                    'edit'     => $this->controller->onEditClick($_POST),
                    'delete'   => $this->controller->onDeleteClick($_POST),
                    default    => ['success' => false,
                                  'message' => __('_sys_unknown_action',
                                                  [':action' => htmlspecialchars($action ?? '')])]
                };
            } catch (\Exception $e) {
                error_log('ControllerDispatcher error: ' . $e->getMessage());
                $response = ['success' => false, 'message' => __('_sys_internal_error')];
            }

            $this->sendJson($response);
        }

        private function sendJson(array $data): void {
            // Discard any accidental output before headers to prevent JSON corruption.
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data);
            exit;
        }
    }