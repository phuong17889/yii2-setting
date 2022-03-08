<?php

/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 8/18/2016
 * Time: 5:14 PM
 */
class m160818_198342_add_store_url extends \yii\db\Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%setting}}', 'store_url', \yii\db\mysql\Schema::TYPE_STRING . '(255) NULL');
    }
}
