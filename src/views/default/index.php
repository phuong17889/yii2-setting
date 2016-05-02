<?php
use navatech\setting\models\Setting;
use navatech\setting\Module;
use yii\web\View;

/**
 * @var $this          View
 * @var $settingParent Setting[]
 * @var $children      Setting[]
 */
$this->title                   = Module::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;
//$form = Activ
echo \kartik\tabs\TabsX::widget([
	'items'        => $items,
	'position'     => \kartik\tabs\TabsX::POS_ABOVE,
	'encodeLabels' => false,
]);
