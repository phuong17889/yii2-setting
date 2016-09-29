<?php
use yii\db\Migration;

class m160929_075128_add_icon extends Migration {

	public function up() {
		$this->addColumn('{{%setting}}', 'icon', \yii\db\mysql\Schema::TYPE_STRING . '(255) NOT NULL DEFAULT ""');
	}

	public function down() {
		echo "m160929_075128_add_icon cannot be reverted.\n";
		return false;
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
