<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 20.12.18.
 * Time: 16.32
 */

class MainController
{
    /** @var Client */
    protected $client;

    /** @var bool|ResponseView  */
    protected $view;

    /**
     * @param Client $client
     */
    public function __construct($client) {
        $this->client = $client;
        $this->view   = $this->getResponseView('ResponseView');
    }

    /**
     * @TODO    implement base security check
     * @return  bool
     */
    public function checkPermissions() {
        return true;
    }


    public function handleDefault(){
        $valid = true;

        $method = 'handle'.ucfirst(strtolower($this->client->getRequestMethod()));

        if (!method_exists($this,$method)) {
            $this->view->renderError('Method not found');
        }

        $validateMethod = 'validate'.ucfirst(strtolower($this->client->getRequestMethod()));
        if (method_exists($this,$validateMethod)) {
            $valid = $this->{$validateMethod}();
        }

        if (empty($valid)) {
            $this->view->renderError('Request not valid');
        }

        try {
            $this->{$method}();
        }
        catch (\Exception $e) {
            $this->view->renderError($e->getMessage(), 500);
        }
    }

    public function handleGet(){
        $this->view->renderError('Method not implemented');
    }

    public function handlePost(){
        $this->view->renderError('Method not implemented');
    }

    public function handlePut(){
        $this->view->renderError('Method not implemented');
    }

    public function handleDelete(){
        $this->view->renderError('Method not implemented');
    }

    /**
     * @return bool
     */
    public function validatePost() {
        return true;
    }

    /**
     * @return bool
     */
    public function validateGet() {
        return true;
    }

    /**
     * @return bool
     */
    public function validateDelete() {
        return true;
    }

    /**
     * @return bool
     */
    public function validatePut() {
        return true;
    }

    /**
     * @param string $classname
     * @param string $segment
     * @return bool
     * @throws Exception
     */
    private function getClass($classname, $segment) {
        $classname = (string)$classname;
        $segment   = (string)$segment;
        if(file_exists(dirname(__DIR__).'/'.$segment.'/'.$classname.'.php')){
            require_once dirname(__DIR__).'/'.$segment.'/'.$classname.'.php';

            if (!class_exists($classname, false)) {
                throw new Exception( 'Class not found: ' . $classname );
            }

            $class = new $classname();

            return $class;
        }

        return false;
    }

    /**
     * @param string $classname
     * @return bool|ResponseView
     * @throws Exception
     */
    public function getResponseView($classname) {
        return $this->getClass(ucfirst($classname), 'view');
    }

    /**
     * @param $modelName
     * @return mixed
     * @throws Exception
     */
    public function getModel($modelName) {
        return $this->getClass(ucfirst($modelName)."Model", 'model');
    }
}