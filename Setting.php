<?php

namespace navatech\setting;

use Yii;
use navatech\setting\models\Setting as SettingModel;

class Setting extends \yii\base\Component
{
    public function get($code, $default = null) {

        if (!$code) {
            return $default;
        }

        $setting = SettingModel::find()->where(['code' => $code])->one();

        if ($setting) {
            return $setting->value;
        } else {
            return $default;
        }
    }

}
