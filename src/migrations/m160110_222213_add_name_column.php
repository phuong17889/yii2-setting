<?php

use yii\db\Schema;
use yii\db\Migration;

class m160110_222213_add_name_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%setting}}', 'name', 'string');

    }

    public function down()
    {
        $this->dropColumn('{{%setting}}', 'name');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
