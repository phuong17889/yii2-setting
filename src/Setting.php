<?php
namespace navatech\setting;

use navatech\setting\models\Setting as SettingModel;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;

/**
 * {@inheritDoc}
 */
class Setting extends Component {

	/**
	 * @param      $code
	 * @param null $default
	 *
	 * @return null|string
	 * @throws InvalidConfigException
	 */
	public function get($code, $default = null) {
		if (!$code) {
			return $default;
		}
		$setting = SettingModel::findOne(['code' => $code]);
		if ($setting) {
			if ($setting->type == SettingModel::TYPE_FILE_PATH) {
				$setting->value = Yii::getAlias($setting->store_dir) . DIRECTORY_SEPARATOR . $setting->value;
			}
			if ($setting->type == SettingModel::TYPE_FILE_URL) {
				if (php_sapi_name() != 'cli') {
					if (strpos($setting->store_url, 'http') !== false) {
						$setting->value = $setting->store_url . '/' . $setting->value;
					} else {
						$setting->value = Url::to([$setting->store_url . '/' . $setting->value], true);
					}
				} else {
					$setting->value = $setting->store_url . '/' . $setting->value;
				}
			}
			if (in_array($setting->type, [
					SettingModel::TYPE_CHECKBOX,
					SettingModel::TYPE_MULTI_SELECT,
				]) && $setting->value != ''
			) {
				$setting->value = explode(",", $setting->value);
			}
			return $setting->value;
		} else {
			if (YII_ENV_DEV && $default == null) {
				throw new InvalidConfigException(Yii::t('setting', 'Record "{0}" doesn\'t exists. Make sure that you\'ve added it in the configuration!', [$code]));
			}
			return $default;
		}
	}

	/**
	 * Return Icon class name
	 *
	 * @param      $code
	 * @param null $default
	 *
	 * @return null|string
	 * @throws InvalidConfigException
	 */
	public function getIcon($code, $default = null) {
		if (!$code) {
			return $default;
		}
		$setting = SettingModel::findOne(['code' => $code]);
		if ($setting) {
			return $setting->icon;
		} else {
			if (YII_ENV_DEV && $default == null) {
				throw new InvalidConfigException(Yii::t('setting', 'Record "{0}" doesn\'t exists. Make sure that you\'ve added it in the configuration!', [$code]));
			}
			return $default;
		}
	}

	/**
	 * @param string $code
	 *
	 * @return string
	 */
	public function __get($code) {
		return $this->get($code);
	}
}
