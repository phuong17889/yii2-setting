<?php

namespace phuongdev89\setting;

use Yii;

class Module extends \phuongdev89\base\Module
{

    public $controllerNamespace = 'phuongdev89\setting\controllers';

    public $enableMultiLanguage = false;

    protected $_isBackend;

    /**
     * Check if module is used for backend application.
     *
     * @return boolean true if it's used for backend application
     */
    public function isBackend()
    {
        if ($this->_isBackend === null) {
            $this->_isBackend = !(strpos(Yii::$app->controllerNamespace, 'backend') === false);
        }
        return $this->_isBackend;
    }
}
