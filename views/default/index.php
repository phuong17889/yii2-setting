<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use itzen\setting\models\Setting;
use itzen\setting\Module;

/* @var $this yii\web\View */

$this->title = Module::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;

$items = [];
foreach ($settingParent as $parent) {
    $item['label'] = Module::t('setting', $parent->name);

    $str = '';
    $children = Setting::find()->where(['parent_id' => $parent->id])->orderBy([
        'sort_order' => SORT_ASC,
        'id' => SORT_ASC
    ])->all();
    foreach ($children as $child) {
        $str .= '<div class="form-group field-blogcatalog-parent_id"><label class="col-lg-2 control-label" for="blogcatalog-parent_id">' . Module::t('setting', $child->name) . '</label><div class="col-lg-10">';

        if ($child->type == 'text') {
            $str .= Html::textInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        } elseif ($child->type == 'email') {
            $str .= Html::textInput("Setting[$child->code]", $child->value, ["class" => "form-control email"]);
        } elseif ($child->type == 'password') {
            $str .= Html::passwordInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        } elseif ($child->type == 'select') {
            $options = [];
            $arrayOptions = explode(',', $child->store_range);
            foreach ($arrayOptions as $option)
                $options[$option] = Module::t('setting', $option);

            $str .= Html::dropDownList("Setting[$child->code]", $child->value, $options, ["class" => "form-control"]);
        } elseif ($child->type == 'multiselect') {
            $options = [];
            $arrayOptions = explode(',', $child->store_range);
            foreach ($arrayOptions as $option)
                $options[$option] = Module::t('setting', $option);

            //            $str .= Html::listBox("Setting[$child->code]", $child->value, $options, [
            //                "class" => "form-control",
            //                "multiple" => "multiple"
            //            ]);


            $str .= \kartik\select2\Select2::widget([
                'name' => "Setting[$child->code]",
                'value' => $child->value,
                //'data' => $options,
                'options' => [
                    'multiple' => true,
                    'placeholder' => \Yii::t('common', 'Select default access rights for new users'),

                ],
                'pluginOptions' => [
                    'tags' => array_keys($options),
                    'createSearchChoice'=> new \yii\web\JsExpression('function() { return null; }'),

                ]
            ]);

            //            echo Select2::widget([
            //                'name' => 'color_1',
            //                'options' => ['placeholder' => 'Select a color ...'],
            //                'pluginOptions' => [
            //                    'tags' => ["red", "green", "blue", "orange", "white", "black", "purple", "cyan", "teal"],
            //                    'maximumInputLength' => 10
            //                ],
            //            ]);


        } elseif ($child->type == 'redactor') {
            $options = [];
            $arrayOptions = explode('|', $child->store_range);
            foreach ($arrayOptions as $option) {
                $options[$option] = $option;
            }

            $str .= '<div class="row"><div class="col-lg-8">' . \yii\imperavi\Widget::widget([
                        'id' => \yii\helpers\Inflector::slug($child->code),
                        'attribute' => "Setting[$child->code]",
                        'value' => $child->value,
                        'plugins' => ['fullscreen', 'imagemanager', 'table', 'fontsize', 'fontcolor', 'clips'],
                        'options' => [
                            'minHeight' => 400,
                            'maxHeight' => 400,
                            'buttonSource' => true,
                            'convertDivs' => false,
                            'removeEmptyTags' => false,
                            'replaceDivs' => false,
                            'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi']),
                            'imageManagerJson' => '/file-storage/index-json',
                            'clipboardUpload' => true,
                            'clipboardUploadUrl' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi']),
                        ]
                    ]
                ) . '</div>';
            $str .= '<div class="col-lg-4"><div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">' . Module::t('setting', 'Clips') . '</h3>
                        </div>
                        <div class="panel-body">'
                . Html::dropDownList("clips", '', $options, ["class" => "form-control insert-clip " . \yii\helpers\Inflector::slug($child->code)])
                . Html::button(Module::t('setting', 'Insert clip'), [
                    "class" => "form-control insert-clip-btn",
                    'data-redactor-id' => \yii\helpers\Inflector::slug($child->code)
                ]) .
                '</div></div>
                    </div></div>';


        }

        $str .= '</div></div>';
    }
    $item['content'] = $str;

    array_push($items, $item);
}
?>

<?php
$form = ActiveForm::begin([
    'id' => 'setting-form',
    'options' => ['class' => 'form-horizontal nav-tabs-custom',],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]);
?>

<?php
echo \yii\bootstrap\Tabs::widget([
    'items' => $items,
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['class' => 'my-class'],
    'clientOptions' => ['collapsible' => false],
]);
?>
<div class="tab-content">
    <div class="form-group">
        <div class="col-lg-3 col-lg-offset-2">
            <?= Html::input('hidden', 'tabHash', '', ['id' => 'tabHash']); ?>
            <?= Html::submitButton(Module::t('setting', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<br/>
<?php ActiveForm::end(); ?>
<?php
$js = <<<JS
    if (location.hash !== ''){
        jQuery('a[href="' + location.hash + '"]').tab('show');
        jQuery('#tabHash').val(location.hash);
    }

    // remember the hash in the URL without jumping
    jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
       if(history.pushState) {
            history.pushState(null, null, '#'+jQuery(e.target).attr('href').substr(1));
       } else {
            location.hash = '#'+jQuery(e.target).attr('href').substr(1);
       }
       jQuery('#tabHash').val('#'+$(e.target).attr('href').substr(1));
    });

    jQuery('.tab-content').on('click','.insert-clip-btn', function(){
        //alert(jQuery('.insert-clip').val());
        jQuery('#'+jQuery(this).data('redactor-id')).redactor('insert.html', jQuery('.insert-clip.'+jQuery(this).data('redactor-id')).val(), false);
        //redactor.insert.html(jQuery('.insert-clip').val(), false);
    });

JS;
$this->registerJs($js);
?>
