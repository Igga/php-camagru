<?php
    class Framework {

        const DEFAULT_CONTROLLER = 'Home';
        const DEFAULT_ACTION = 'index';

        public static function start() {
            self::init();
            session_start();
            spl_autoload_register(array(__CLASS__, 'classLoader'));
            self::router();
        }

        private static function init() {
            define('ROOT', getcwd().DIRECTORY_SEPARATOR);
            define('CONFIG_PATH', ROOT.'config'.DIRECTORY_SEPARATOR);
            define('CORE_PATH', ROOT.'core'.DIRECTORY_SEPARATOR);
            define('MODEL_PATH', ROOT.'models'.DIRECTORY_SEPARATOR);
            define('VIEW_PATH', ROOT.'views'.DIRECTORY_SEPARATOR);
            define('CONTROLLER_PATH', ROOT.'controllers'.DIRECTORY_SEPARATOR);
            define('TEMPLATE_PATH', VIEW_PATH.'templates'.DIRECTORY_SEPARATOR);

            define('CONTROLLER', isset($_REQUEST['controller']) ? ucfirst($_REQUEST['controller']) : self::DEFAULT_CONTROLLER);
            define('ACTION', isset($_REQUEST['action']) ? $_REQUEST['action'] : self::DEFAULT_ACTION);

            require CONFIG_PATH.'db.conf.php';
            require CORE_PATH.'DB.php';
            require CORE_PATH.'BaseController.php';
            require CORE_PATH.'BaseModel.php';
        }

        private static function classLoader($className) {
            if (strpos($className, 'Controller') !== false && file_exists(CONTROLLER_PATH.$className.'.php')) {
                require_once CONTROLLER_PATH.$className.'.php';
            }
            if (strpos($className, 'Model') !== false && file_exists(MODEL_PATH.$className.'.php')) {
                require_once MODEL_PATH.$className.'.php';
            }
        }

        private static function router() {
            $controllerName = CONTROLLER.'Controller';
            $actionName = ACTION.'Action';

            if (class_exists($controllerName) === false) {
                return;
            }
            $controller = new $controllerName;
            if (method_exists($controllerName, $actionName) === false) {
                return;
            }
            $controller->$actionName();
        }
    }
