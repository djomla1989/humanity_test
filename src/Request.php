<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 18.12.18.
 * Time: 20.55
 */
require_once(dirname(__DIR__) . '/src/model/User.php');
require_once(dirname(__DIR__) . '/src/model/UserVacationRequest.php');

class Request
{
    /**
     * Get all user data
     * @param int $id
     * @return array
     */
    public function read($id) {
        /** @var User $user */
        $user  = new User();
        $data  = $user->getUserData($id);
        return $data;
    }
}