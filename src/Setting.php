<?php

namespace phuongdev89\setting;

use phuongdev89\setting\models\Setting as SettingModel;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;

/**
 * {@inheritDoc}
 * @method string getValue(string $code, string $default = '')
 * @method string getIcon(string $code, string $default = '')
 * @method string getDesc(string $code, string $default = '')
 * @method string getStoreRange(string $code, string $default = '')
 * @method string getName(string $code, string $default = '')
 * @method string getType(string $code, string $default = '')
 * @method string getStoreDir(string $code, string $default = '')
 * @method string getSortOrder(string $code, string $default = '')
 * @method string getStoreUrl(string $code, string $default = '')
 */
class Setting extends Component
{

    /**
     * @param      $code
     * @param null $default
     *
     * @return null|string
     * @throws InvalidConfigException
     */
    public function get($code, $default = null)
    {
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
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function __call($name, $params)
    {
        $code = $params[0] ?? false;
        $default = $params[1] ?? '';
        if (!$code) {
            return $default;
        }
        $setting = SettingModel::findOne(['code' => $code]);
        if ($setting !== null) {
            $name = str_replace('get', '', $name);
            $name = substr(strtolower(preg_replace("/([A-Z]+)/", "_$1", $name)), 1);
            if ($setting->hasAttribute($name)) {
                return $setting->$name;
            } else {
                return $default;
            }
        } else {
            if (YII_ENV_DEV && $default == null) {
                throw new InvalidConfigException(Yii::t('setting', 'Record "{0}" doesn\'t exists. Make sure that you\'ve added it in the configuration!', [$code]));
            }
            return $default;
        }
    }

    /**
     * @param string $name
     *
     * @return string
     * @throws InvalidConfigException
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}
