<?php

namespace navatech\setting;


use yii\web\AssetBundle;

class RedactorPlugins extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';

    public $sourcePath = '@vendor/navatech/yii2-setting/assets';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $js = [
        'js/customColors.js',
        //'js/clips.js'
    ];

    public $depends = [
        'yii\imperavi\ImperaviRedactorAsset',
    ];
}