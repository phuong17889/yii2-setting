<?php
namespace navatech\setting;

use navatech\setting\models\Setting as SettingModel;
use Yii;
use yii\base\Component;

class Setting extends Component {

	public function get($code, $default = null) {
		if (!$code) {
			return $default;
		}
		$setting = SettingModel::findOne(['code' => $code]);
		if ($setting) {
			return $setting->value;
		} else {
			return $default;
		}
	}
}
