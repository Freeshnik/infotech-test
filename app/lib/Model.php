<?php

namespace App;

class Model extends \yii\base\Model
{
    public $isNewRecord = true;

    /** @var  ActiveRecord */
    protected $_entity;

    /**
     * @param null|ActiveRecord $activeRecord
     */
    public function __construct(?ActiveRecord $activeRecord = null)
    {
        if ($activeRecord) {
            $this->_entity = $activeRecord;
            $this->isNewRecord = $this->_entity->isNewRecord;
        }
        parent::__construct();
    }
}
