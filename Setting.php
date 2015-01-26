<?php

namespace funson86\setting;
use Yii;
use funson86\setting\models\Setting as SettingModel;

class Setting extends \yii\base\Component
{
    public function get($code)
    {
        if(!$code) return ;

        $setting = SettingModel::find()->where(['code' => $code])->one();

        if($setting)
            return $setting->value;
        else
            return ;
    }

}
