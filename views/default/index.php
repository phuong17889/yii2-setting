<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use itzen\setting\models\Setting;
use itzen\setting\Module;

$this->title = Module::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;

$items = [];
foreach ($settingParent as $parent) {
    $item['label'] = Module::t('setting', $parent->code);

    $str = '';
    $children = Setting::find()->where(['parent_id' => $parent->id])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
    foreach ($children as $child) {
        $str .= '<div class="form-group field-blogcatalog-parent_id"><label class="col-lg-2 control-label" for="blogcatalog-parent_id">' . Module::t('setting', $child->code) . '</label><div class="col-lg-3">';

        if ($child->type == 'text')
            $str .= Html::textInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        elseif ($child->type == 'password')
            $str .= Html::passwordInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        elseif ($child->type == 'select') {
            $options = [];
            $arrayOptions = explode(',', $child->store_range);
            foreach ($arrayOptions as $option)
                $options[$option] = Module::t('setting', $option);

            $str .= Html::dropDownList("Setting[$child->code]", $child->value, $options, ["class" => "form-control"]);
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
            'options' => [
                'class' => 'form-horizontal nav-tabs-custom',
            ],
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
            <?= Html::input('hidden', 'tabHash', '', ['id'=>'tabHash']); ?>
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
        
JS;
    
    $this->registerJs($js);
?>