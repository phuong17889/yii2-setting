<?php

use phuong17889\setting\models\Setting;

/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 8/18/2016
 * Time: 5:14 PM
 */
class m160901_198342_change_setting_type extends \yii\db\Migration
{

    public function safeUp()
    {
        $this->alterColumn('{{%setting}}', 'type', \yii\db\mysql\Schema::TYPE_STRING . '(255) NOT NULL');
        /**@var Setting[] $settings */
        $settings = Setting::find()->all();
        $type = array_keys(Setting::TYPE);
        foreach ($settings as $setting) {
            foreach ($type as $key => $item) {
                if ($setting->type == ($key - 1)) {
                    $setting->updateAttributes(['type' => $item]);
                }
            }
        }
    }
}
