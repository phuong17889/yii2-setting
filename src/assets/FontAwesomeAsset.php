<?php
/**
 * Created by phuong17889.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    17/02/2016
 * @time    12:09 CH
 * @version 1.0.0
 */

namespace phuong17889\setting\assets;

use yii\web\AssetBundle;

/**
 * This will register asset for FontAwesome
 * {@inheritDoc}
 */
class FontAwesomeAsset extends AssetBundle
{

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->depends = [
            'yii\web\JqueryAsset',
        ];
        $this->sourcePath = '@bower/fontawesome';
        $this->css = [
            'css/font-awesome.min.css',
        ];
    }
}
