<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 20.12.18.
 * Time: 16.34
 */

require_once('MainController.php');

class User extends MainController
{

    public function handlePost() {

    }

    public function handleGet() {
        /** @var UserModel $userModel */
        $userModel = $this->getModel("User");
        $data = $userModel->getUserData($this->client->getId());
        $this->view->renderJSONOutput($data);
    }
}