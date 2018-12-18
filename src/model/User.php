<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 18.12.18.
 * Time: 21.05
 */

require_once(dirname(__DIR__) . '/../db/DBManager.php');

class User extends DBManager
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
            ->all();
    }
}