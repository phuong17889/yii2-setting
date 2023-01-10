<?php
/**
 * Created by phuongdev89.
 * @author  Phuong
 * @email   phuongdev89@gmail.com
 * @date    5/10/2016
 * @time    11:04 AM
 */

namespace phuongdev89\setting\assets;

use yii\web\AssetBundle;

class AwesomeBootstrapCheckboxAsset extends AssetBundle
{

    public $sourcePath = '@bower/awesome-bootstrap-checkbox';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'phuongdev89\setting\assets\FontAwesomeAsset',
    ];

    public $css = [
        'awesome-bootstrap-checkbox.css',
    ];
}
