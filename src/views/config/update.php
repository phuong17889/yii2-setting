<?php
/* @var $this View */

/* @var $model Setting */

use phuongdev89\setting\models\Setting;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('setting', 'Update Setting') . ': ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('setting', 'Setting'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = [
    'label' => $model->name,
    'url' => [
        'view',
        'id' => $model->id,
    ],
];
$this->params['breadcrumbs'][] = Yii::t('setting', 'Update');
?>
<div class="phuongdev89-setting">
    <div class="col-sm-12">
        <div class="setting-update">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
