<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 18.12.18.
 * Time: 21.07
 */

require_once(dirname(__DIR__) . '/../db/DBManager.php');

class VacationModel extends DBManager
{
    protected $table = 'user_vacation_request';

    /**
     * @param int $id
     * @return array
     */
    public function getById($id) {
        return $this->query("SELECT * FROM ".$this->table." WHERE id = ?")
            ->bind($id)
            ->asArray()
            ->one();
    }

    /**
     * @param array $data
     * @return int
     */
    public function save($data = array()) {
        return $this->query("INSERT INTO ".$this->table." SET 
            user_id = ?, 
            date_from = ?, 
            date_to = ?, 
            approver_id = ?, 
            number_of_days = ?")
        ->bind($data['userId'], $data['dateFrom'], $data['dateTo'], $data['approvalUser'], $data['numberOfDays'])
        ->run();

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateStatus($data = array()) {
        return $this->query("UPDATE ".$this->table." SET status = ? WHERE id = ? AND status = ?")
            ->bind($data['status'], $data['id'], 'pending')
            ->run();
    }

    /**
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function getUserRequests($id, $status = 'pending') {
        return $this->query("SELECT * FROM $this->table WHERE user_id = ? and status = ?")
            ->bind($id, $status)
            ->asArray()
            ->all();
    }
}