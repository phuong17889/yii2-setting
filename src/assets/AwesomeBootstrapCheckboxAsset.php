<?php
/**
 * Created by phuong17889.
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    5/10/2016
 * @time    11:04 AM
 */

namespace phuong17889\setting\assets;

use yii\web\AssetBundle;

class AwesomeBootstrapCheckboxAsset extends AssetBundle
{

    public $sourcePath = '@bower/awesome-bootstrap-checkbox';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'phuong17889\setting\assets\FontAwesomeAsset',
    ];

    public $css = [
        'awesome-bootstrap-checkbox.css',
    ];
}
