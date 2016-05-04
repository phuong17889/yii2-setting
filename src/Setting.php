<?php
namespace navatech\setting;

use navatech\setting\models\Setting as SettingModel;
use Yii;
use yii\base\Component;

class Setting extends Component {

	/**
	 * @param      $code
	 * @param null $default
	 *
	 * @return null|string
	 */
	public function get($code, $default = null) {
		if(!$code) {
			return $default;
		}
		$setting = SettingModel::findOne(['code' => $code]);
		if($setting) {
			return $setting->value;
		} else {
			return $default;
		}
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public function __get($name) {
		$setting = SettingModel::findOne(['code' => $name]);
		if($setting) {
			return $setting->value;
		} else {
			return '';
		}
	}
}
