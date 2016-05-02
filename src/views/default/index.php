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
$form                          = ActiveForm::begin([
	'id' => 'setting',
]);
$items                         = [];

echo TabsX::widget([
	'items'        => [
		[
			'label'   => '<i class="glyphicon glyphicon-home"></i> Home',
			'content' => 'sdfsdf',
			'active'  => true,
		],
		[
			'label'   => '<i class="glyphicon glyphicon-user"></i> Profile',
			'content' => 'sdfsdfsdf',
		],
	],
	'bordered'     => true,
	'position'     => TabsX::POS_ABOVE,
	'encodeLabels' => false,
]);
ActiveForm::end();