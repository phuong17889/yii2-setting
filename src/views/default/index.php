<?php
use kartik\tabs\TabsX;
use navatech\setting\models\Setting;
use navatech\setting\Module;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/**
 * @var $this          View
 * @var $settingParent Setting[]
 * @var $children      Setting[]
 */
$this->title                   = Module::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;
$items                         = [];
echo TabsX::widget([
	'items'        => Setting::getItems(),
	'bordered'     => true,
	'position'     => TabsX::POS_ABOVE,
	'encodeLabels' => false,
]);
