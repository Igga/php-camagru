<?php
    class BaseController {
        public function isLogged() {
            return isset($_SESSION['userid']);
        }

        protected function redirect($url) {
            header("Location:${url}");
        }

        protected function view($path, $vars = array()) {
            ob_start();
            extract($vars);
            require VIEW_PATH.$path.'View.php';
            $content = ob_get_clean();
            require TEMPLATE_PATH.'main.php';
        }
    }
