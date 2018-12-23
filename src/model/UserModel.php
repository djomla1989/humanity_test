<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 18.12.18.
 * Time: 21.05
 */

require_once(dirname(__DIR__) . '/../db/DBManager.php');

class UserModel extends DBManager
{
    protected $table = 'user';

    /**
     * @param int $id
     * @return array
     */
    public function getUserData($id) {
        return $this->query("SELECT * FROM $this->table WHERE id = ?")
            ->bind($id)
            ->asArray()
            ->one();
    }

    /**
     * @param int $userId
     * @param int $number
     * @return mixed
     */
    public function updateNumberOfDays($userId, $number) {
        return $this->query("UPDATE ".$this->table." SET vacation_days = (vacation_days - ?) WHERE id = ?")
            ->bind($number, $userId)
            ->run();

    }
}