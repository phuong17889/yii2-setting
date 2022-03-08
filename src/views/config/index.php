<?php
/* @var $this View */
/* @var $searchModel SettingSearch */

/* @var $dataProvider ActiveDataProvider */

use kartik\editable\Editable;
use kartik\grid\GridView;
use phuong17889\setting\models\search\SettingSearch;
use phuong17889\setting\models\Setting;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phuong17889-setting">
    <div class="col-sm-12">
        <div class="setting-index">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                <?= Html::a(Yii::t('setting', 'Create Setting'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'pjax' => true,
                'hover' => true,
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'persistResize' => false,
                'panel' => [
                    'type' => 'primary',
                    'heading' => Yii::t('setting', 'Setting configure'),
                ],
                'containerOptions' => ['style' => 'overflow: auto'],
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => 'kartik\grid\ExpandRowColumn',
                        'value' => function (Setting $model, $key, $index, $column) {
                            return GridView::ROW_COLLAPSED;
                        },
                        'detail' => function (Setting $model, $key, $index, $column) {
                            return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
                        },
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                        'expandOneOnly' => true,
                    ],
                    [
                        'attribute' => 'parent_id',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map(Setting::find()->where([
                            'type' => 0,
                            'parent_id' => 0,
                        ])->orderBy(['sort_order' => SORT_ASC])->all(), 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => Yii::t('setting', 'Parent tab')],
                        'value' => function (Setting $data) {
                            $parent = $data->findOne($data->parent_id);
                            return $parent !== null ? $parent->getName() : '';
                        },
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'code',
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'name',
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'type',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => Setting::TYPE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => Yii::t('setting', 'Type')],
                        'value' => function (Setting $data) {
                            return Setting::TYPE[$data->type];
                        },
                        'refreshGrid' => true,
                        'editableOptions' => [
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => Setting::TYPE,
                        ],
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'sort_order',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}&nbsp;{delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
