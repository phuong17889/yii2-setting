<?php
use yii\db\Migration;
use yii\db\mysql\Schema;

class m170418_041322_fix_length_of_range extends Migration {

	public function safeUp() {
		$this->alterColumn('{{%setting}}', 'store_range', Schema::TYPE_TEXT);
	}

	public function safeDown() {
		echo "m170418_041322_fix_length_of_range cannot be reverted.\n";
		return false;
	}
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m170418_041322_fix_length_of_range cannot be reverted.\n";

		return false;
	}
	*/
}
