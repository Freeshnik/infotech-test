<?php

namespace App;

use yii\helpers\ArrayHelper;

class App
{

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @var []
     */
    protected $config;

    /**
     * @return static
     */
    public static function i()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param string $module_name
     * @return []
     */
    public function getConfig($module_name = MODULE_NAME)
    {
        if (empty($this->config[$module_name])) {
            $app_config    = require(dirname(__DIR__) . '/config/config.php');
            $module_config = $local_config = [];

            $config_file_module = dirname(__DIR__) . '/config/modules/' . $module_name . '.php';
            if (is_file($config_file_module) && is_readable($config_file_module)) {
                $module_config = require(dirname(__DIR__) . '/config/modules/' . $module_name . '.php');
            }
            $config_file_local = dirname(__DIR__) . '/config/config_local.php';
            if (is_file($config_file_local) && is_readable($config_file_local)) {
                $local_config = require(dirname(__DIR__) . '/config/config_local.php');
            }
            $this->config[$module_name] = ArrayHelper::merge(
                $app_config,
                $module_config,
                $local_config
            );
        }
        return $this->config[$module_name];
    }

    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    public function getApp()
    {
        return \Yii::$app;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return Request::i();
    }

    public function getResponse()
    {
        return Response::i();
    }

    /**
     * @return \yii\db\Connection
     */
    public function getDb()
    {
        return \Yii::$app->db;
    }

    /**
     * @return \yii\caching\CacheInterface
     */
    public function getCache()
    {
        return \Yii::$app->cache;
    }

    /**
     * @return \yii\web\IdentityInterface
     */
    public function getCurrentUser()
    {
        return \Yii::$app->user->identity;
    }

    /**
     * @return \yii\base\Module
     */
    public function getCurrentModule()
    {
        return \Yii::$app->controller->module;
    }

    public function getFaceDomain()
    {
        return \Yii::$app->params['domains']['face'];
    }

    /**
     * @param string $type
     * @param        $message
     */
    public function setFlash($type = 'success', $message)
    {
        \Yii::$app->getSession()->setFlash($type, $message);
    }

    public function getFlashes()
    {
        return \Yii::$app->getSession()->getAllFlashes();
    }

}