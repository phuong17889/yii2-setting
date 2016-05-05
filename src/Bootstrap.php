<?php
namespace navatech\setting;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Blogs module bootstrap class.
 */
class Bootstrap implements BootstrapInterface {

	/**
	 * Bootstrap method to be called during application bootstrap stage.
	 *
	 * @param Application $app the application currently running
	 */
	public function bootstrap($app) {
		// TODO: cần set lại alias, viết thêm hàm gen ra biến giống như multi language
//		\Yii::setAlias('@setting')
	}
}
