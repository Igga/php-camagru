<?php
    class UserModel extends BaseModel {
        public static $USER_TABLE = 'users';

        public $id;
        public $login;
        public $email;
        public $password;
        public $activated;

        public function __construct($id, $login, $email, $password, $activated) {
            parent::__construct();
            $this->id = $id;
            $this->login = $login;
            $this->email = $email;
            $this->password = $password;
            $this->activated = $activated;
        }

        public function sendActivationLink() {
            $time = (new DateTime())->getTimestamp();
            $hash = md5($time.$this->password.$time);
            $link = "http://camagru.dev/user/activate?id={$this->id}&hash=${hash}&time=${time}";
            $message = "Hello {$this->login}!\r\nYour activation link:\r\n${link}";
            $message = wordwrap($message, 70, "\r\n");
            $headers = 'From: noreply@camagru.dev' . "\r\n" .
                'Reply-To: noreply@camagru.dev' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($this->email, 'Camagru activation', $message, $headers);
        }

        public function setLogged() {
            $_SESSION['userid'] = $this->id;
        }

        public function setUnLogged() {
            unset($_SESSION['userid']);
        }

        public function setActive($status) {
            $table = UserModel::$USER_TABLE;
            $query = "UPDATE $table SET activated = :status WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam('status', $status);
            $stmt->bindParam('id', $this->id);
            $stmt->execute();

            $this->activated = 1;
        }

        public static function getUser() {
            return isset($_SESSION['userid']) ? self::getUserById($_SESSION['userid']) : null;
        }

        public static function loginUser($login, $password) {
            $user = UserModel::getUserByLogin($login);
            if ($user->password === md5($password)) {
                $user->setLogged();
                return $user;
            }
            $user = UserModel::getUserByEmail($login);
            if ($user->password === md5($password)) {
                $user->setLogged();
                return $user;
            }

            throw new Exception('Invalid username or password');
        }

        public static function logoutUser() {
            $user = UserModel::getUser();
            if ($user) {
                $user->setUnLogged();
            }
        }

        public static function registerUser($login, $email, $password, $repassword)  {
            if (!preg_match('/^[a-zA-Z0-9]+/', $login)) {
                throw new Exception('Login must be only from english letters or numbers');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email');
            }
            if(strlen($login) > 49) {
                throw new Exception('Login is too long');
            }
            if (strlen($email) > 49) {
                throw new Exception('Email is too long');
            }
            if (strlen($password) < 5) {
                throw new Exception('Password is too short');
            }
            if (strtolower($password) === $password || strtoupper($password) === $password) {
                throw new Exception('Password must contains at least one upper letter and one lower');
            }
            if ($password !== $repassword) {
                throw new Exception('Password not match retype password');
            }
            if (self::getUserByLogin($login)) {
                throw new Exception('User with this login already exists');
            }
            if (self::getUserByEmail($email)){
                throw new Exception('User with this email already exists');
            }

            $table = UserModel::$USER_TABLE;
            $query = "INSERT INTO $table (login, email, password) VALUES (?, ?, ?)";
            $db = new Db();
            $stmt = $db->connect()->prepare($query);
            $stmt->execute([$login, $email, md5($password)]);

            $user = self::getUserById($db->connection->lastInsertId());
            if (!$user) {
                throw new Exception('Fatal error! Try later please');
            }
            $user->sendActivationLink();

            return $user;
        }

        public static function activateUser($id, $hash, $time) {
            $user = self::getUserById($id);
            if (!$user) {
                throw new Exception('User not found');
            }
            if ($user->activated == 1) {
                throw new Exception('Already activated');
            }
            if (md5($time.$user->password.$time) !== $hash) {
                throw new Exception('Invalid activation link');
            }
            $user->setActive(1);

            return $user;
        }

        public static function getUserByLogin($login) {
            $table = UserModel::$USER_TABLE;
            $query = "SELECT * FROM $table WHERE login = :login";
            $db = new DB();
            $stmt = $db->connect()->prepare($query);
            $stmt->bindParam('login', $login);
            $stmt->execute();

            return self::prepareUser($stmt);
        }

        public static function getUserByEmail($email) {
            $table = UserModel::$USER_TABLE;
            $query = "SELECT * FROM $table WHERE email = :email";
            $db = new DB();
            $stmt = $db->connect()->prepare($query);
            $stmt->bindParam('email', $email);
            $stmt->execute();

            return self::prepareUser($stmt);
        }

        public static function getUserById($id) {
            $table = UserModel::$USER_TABLE;
            $query = "SELECT * FROM $table WHERE id = :id";
            $db = new DB();
            $stmt = $db->connect()->prepare($query);
            $stmt->bindParam('id', $id);
            $stmt->execute();

            return self::prepareUser($stmt);
        }

        public static function prepareUser($stmt) {
            if($stmt->rowCount() === 0) {
                return null;
            }
            $row = $stmt->fetch();
            return new self($row['id'], $row['login'], $row['email'], $row['password'], $row['activated']);
        }
    }
