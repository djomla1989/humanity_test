<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 20.12.18.
 * Time: 16.25
 */

class Client
{
    /**
     * get the HTTP request method
     *
     * @return string
     */
    public function getRequestMethod() {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isPost(){
        return $this->getRequestMethod() === 'POST';
    }

    /**
     * @return bool
     */
    public function isGet(){
        return $this->getRequestMethod() === 'GET';
    }

    /**
     * @return bool
     */
    public function isPut(){
        return $this->getRequestMethod() === 'PUT';
    }

    /**
     * @return bool
     */
    public function isDelete(){
        return $this->getRequestMethod() === 'DELETE';
    }

    /**
     * @return string
     */
    public function isJSon(){
        if(isset($_SERVER['CONTENT_TYPE'])){
            $headers = explode(';',$_SERVER['CONTENT_TYPE']);
            if(isset($headers[0]) && strtolower($headers[0] == 'application/json')){
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @return mixed
     */
    public function getJSonRequest(){
        if($this->isJSon()){
            return json_decode(stripslashes($this->getRawRequest()), true);
        }
        return array();
    }

    /**
     *
     * @return mixed
     */
    public function getRawRequest(){
        return file_get_contents('php://input');
    }

    /**
     * @return string
     */
    public function getControllerName() {
        $request = $_SERVER["REQUEST_URI"];
        $parts   = explode("/",$request);
        return ucfirst($parts[2]);
    }

    /**
     * @return string
     */
    public function getId() {
        $request = $_SERVER["REQUEST_URI"];
        $parts   = explode("/",$request);
        return ucfirst($parts[3]);
    }
}