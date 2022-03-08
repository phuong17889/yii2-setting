<?php

use kartik\tabs\TabsX;
use phuong17889\setting\assets\AwesomeBootstrapCheckboxAsset;
use phuong17889\setting\models\Setting;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var $this  View
 * @var $title string
 * @var $code  string
 */
AwesomeBootstrapCheckboxAsset::register($this);
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$items = [];
if (Yii::$app->session->hasFlash('alert')) {
    echo Alert::widget(Yii::$app->session->getFlash('alert'));
}
?>
<div class="phuong17889-setting">
    <div class="col-sm-12">
        <?php if (YII_ENV_DEV): ?>
            <p class="pull-right">
                <?= Html::a(Yii::t('setting', 'Setting'), ['config/index'], [
                    'class' => 'btn btn-primary',
                ]) ?>
            </p>
        <?php endif; ?>
        <?= TabsX::widget([
            'items' => Setting::getItems($code),
            'bordered' => true,
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false,
        ]); ?>
    </div>
</div>
