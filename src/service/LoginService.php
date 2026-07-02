<?php
    require_once __DIR__ . '/../repository/BaseRepo.php';

    // Class handles user authentication and session management.
    class LoginService {
        private BaseRepo $baseRepo;

        public function __construct() {
            $this->baseRepo = new BaseRepo();
        }

        public function login(string $username, string $password): bool {
            $foundUser = $this->baseRepo->findUserByCredentials($username, $password);

            if (!$foundUser) return false; 

            $_SESSION['user'] = [
                'id'          => $foundUser['id'],
                'name'        => $foundUser['name'],
                'permission'  => $foundUser['permission'],
            ];

            return true;
        }

        public function logout(): bool {
            session_unset();
            session_destroy();

            // Expire the session cookie in the browser
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(), 
                    '',
                    time() - 42000, 
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
            return true;
        }
    }