<?php

namespace App;

use yii\web\HttpException;

class Response
{

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @return self
     */
    public static function i()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $message
     * @throws HttpException
     */
    public function set404($message = '')
    {
        throw new HttpException(404, $message);
    }

    /**
     * @param string $message
     * @throws HttpException
     */
    public function set403($message = '')
    {
        throw new HttpException(403, $message);
    }

    public function setJsonFormat()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function setXmlFormat($data = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        if ($data) {
            if (is_array($data)) {
                \Yii::$app->response->data = $data;
            } else {
                \Yii::$app->response->content = $data;
            }
        }
    }

    public function redirect($url, $statusCode = 302, $checkAjax = true) {
        return \Yii::$app->response->redirect($url, $statusCode, $checkAjax);
    }

}