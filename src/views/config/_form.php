<?php
use insolita\iconpicker\Iconpicker;
use kartik\widgets\Select2;
use navatech\setting\models\Setting;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'parent_id')->widget(Select2::className(), [
		'data'          => Setting::parentDependent(),
		'options'       => ['placeholder' => Yii::t('setting', 'Select a parent tab ...')],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]) ?>

	<?= $form->field($model, 'code')->textInput([
		'maxlength'   => true,
		'placeholder' => Yii::t('setting', 'Code key of setting'),
	]) ?>
	<div class="icon" style="display: <?= in_array($model->type, [
		Setting::TYPE_ACTION,
		Setting::TYPE_GROUP,
	]) ? 'block' : 'none' ?>;">
		<?= $form->field($model, 'icon')->widget(Iconpicker::className(), [
			'iconset'       => 'glyphicon',
			'pickerOptions' => ['class' => 'btn btn-primary'],
			'clientOptions' => [
				'placement' => 'bottom',
				'search'    => false,
			],
		]) ?>
	</div>
	<?= $form->field($model, 'name')->textInput([
		'maxlength'   => true,
		'placeholder' => Yii::t('setting', 'Name of setting'),
	]) ?>

	<?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'type')->widget(Select2::className(), [
		'data'          => Setting::TYPE,
		'options'       => ['placeholder' => Yii::t('setting', 'Select a type ...')],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]) ?>

	<?= $form->field($model, 'store_range')->textarea([
		'rows'        => 6,
		'placeholder' => Yii::t('setting', 'Required if type in (select, multiselect, checkbox, radio), supported string with comma, json, callback function.') . ' 
Example: 
 - String: 1,2,3 or A,bcd,ef
 - Json: {"0" : "abc", "1" : "def"}
 - Callback: app\models\Setting::getItems()',
	]); ?>
	<div class="store" style="display: <?= in_array($model->type, [
		Setting::TYPE_FILE_URL,
		Setting::TYPE_FILE_PATH,
	]) ? 'block' : 'none' ?>;">
		<?= $form->field($model, 'store_dir')->textInput([
			'maxlength'   => true,
			'placeholder' => Yii::t('setting', 'Required if type in (file, url). Example & default: @app/web/uploads'),
		]) ?>
		<?= $form->field($model, 'store_url')->textInput([
			'maxlength'   => true,
			'placeholder' => Yii::t('setting', 'Required if type in (file, url). Example: http://yiiframework.com/uploads'),
		]) ?>
	</div>
	<?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'sort_order')->textInput([
		'type'  => 'number',
		'value' => $model->isNewRecord ? 1 : $model->sort_order,
	]) ?>
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('setting', 'Create') : Yii::t('setting', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<script>
	$(document).on("change", "#setting-type", function () {
		var th = $(this);
		if (th.val() == 'file_path' || th.val() == 'file_url') {
			$(".store").slideDown();
			$(".icon").slideUp();
		} else if (th.val() == 'group' || th.val() == 'action') {
			$(".store").slideUp();
			$(".icon").slideDown();
		} else {
			$(".icon").slideUp();
			$(".store").slideUp();
		}
	});
</script>