<?php
    class UserController extends BaseController {

        const LOGIN_PAGE = 'Login';
        const REGISTER_PAGE = 'Register';
        const INFO_PAGE = 'Info';

        public function loginAction() {
            if ($this->isLogged()) {
                $this->redirect('/');
                return;
            }

            if (!isset($_POST['login']) || !isset($_POST['password'])) {
                $this->view(self::LOGIN_PAGE);
                return;
            }

            try {
                UserModel::loginUser($_POST['login'], $_POST['password']);
                $this->redirect('/');
            } catch (Exception $e) {
                $this->view(self::LOGIN_PAGE, array('error' => $e->getMessage()));
            }
        }

        public function registerAction() {
            if ($this->isLogged()) {
                $this->redirect('/');
                return;
            }

            if (!isset($_POST['login']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['repassword'])) {
                $this->view(self::REGISTER_PAGE);
                return;
            }

            try {
                UserModel::registerUser($_POST['login'], $_POST['email'], $_POST['password'], $_POST['repassword']);
                $this->redirect('/');
            } catch (Exception $e) {
                $this->view(self::REGISTER_PAGE, array('error' => $e->getMessage()));
            }
        }

        public function activateAction() {
            try {
                UserModel::activateUser($_GET['id'], $_GET['hash'], $_GET['time']);
                $this->redirect('/');
            } catch (Exception $e) {
                $this->view(self::INFO_PAGE, array('error' => $e->getMessage()));
            }
        }

        public function logoutAction() {
            if ($this->isLogged()) {
                UserModel::logoutUser();
            }
            $this->redirect('/');
        }
    }
