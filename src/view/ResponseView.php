<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 21.12.18.
 * Time: 21.03
 */

class ResponseView
{
    /**
     * @param array $data
     * @param int $httpCode
     */
    public function renderJSONOutput($data = array(), $httpCode = 200) {
        if (!is_array($data)) {
            $data = array($data);
        }
        $data = json_encode($data);
        http_response_code($httpCode);

        //send headers
        header('Content-length: '.strlen($data));
        header('Content-type: application/json');

        //send output
        print $data;
        die();
    }

    /**
     * @param null|string $message
     * @param int $errorNumber
     */
    public function renderError($message = null, $errorNumber = 200) {
        $data = array(
            'status'    => 'error',
            'code'      => $errorNumber,
            'message'   => !empty($message) ? $message : ''
        );
        $this->renderJSONOutput($data);
    }

    /**
     * @param $data
     * @param null $message
     */
    public function renderSuccess($data, $message = null) {
        $data = array(
            'status'    => 'success',
            'data'      => $data,
            'message'   => !empty($message) ? $message : ''
        );
        $this->renderJSONOutput($data);
    }
}