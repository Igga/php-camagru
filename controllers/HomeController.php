<?php
    class HomeController extends BaseController {

        const HOME_PAGE = 'Home';

        public function indexAction() {
            $this->view(self::HOME_PAGE);
        }
    }
