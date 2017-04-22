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
	<div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'parent_id')->widget(Select2::className(), [
				'data'          => Setting::parentDependent(),
				'options'       => ['placeholder' => Yii::t('setting', 'Select a parent tab ...')],
				'pluginOptions' => [
					'allowClear' => true,
				],
			]) ?>
			<?= $form->field($model, 'type')->widget(Select2::className(), [
				'data'          => Setting::TYPE,
				'options'       => ['placeholder' => Yii::t('setting', 'Select a type ...')],
				'pluginOptions' => [
					'allowClear' => true,
				],
			]) ?>

			<?= $form->field($model, 'code')->textInput([
				'maxlength'   => true,
				'placeholder' => Yii::t('setting', 'Code key of setting'),
			]) ?>
			<?= $form->beginField($model, 'name') ?>
			<?= Html::activeLabel($model, 'name', ['class' => 'control-label']) ?>
			<div class="<?= in_array($model->type, [
				Setting::TYPE_ACTION,
				Setting::TYPE_GROUP,
			]) ? 'input-group' : '' ?>">
				    <span class="input-group-btn icon-setting" style="display: <?= in_array($model->type, [
					    Setting::TYPE_ACTION,
					    Setting::TYPE_GROUP,
				    ]) ? 'block' : 'none' ?>;">
					    <?= Iconpicker::widget([
						    'model'         => $model,
						    'attribute'     => 'icon',
						    'pickerOptions' => ['class' => 'btn btn-default'],
						    'clientOptions' => [
							    'placement' => 'bottom',
							    'search'    => false,
						    ],
					    ]) ?>
				    </span>
				<?= Html::activeTextInput($model, 'name', ['class' => 'form-control']) ?>
			</div>
			<?= Html::error($model, 'name', ['class' => 'help-block']) ?>
			<?= $form->endField() ?>
			<?= $form->field($model, 'sort_order')->textInput([
				'type'  => 'number',
				'value' => $model->isNewRecord ? 1 : $model->sort_order,
			]) ?>
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
		</div>
		<div class="col-sm-8">

			<?= $form->field($model, 'store_range', ['template' => '{label}<a class="btn btn-link" data-toggle="modal" href="#custom-store-range"><i class="fa fa-plus"></i> ' . Yii::t('setting', 'Custom Store Range') . '</a>{input}{error}'])->textarea([
				'rows'        => 6,
				'placeholder' => Yii::t('setting', 'Required if type in (select, multiselect, checkbox, radio), supported string with comma, json, callback function.') . ' 
Example: 
 - String: 1,2,3 or A,bcd,ef
 - Json: {"0" : "abc", "1" : "def"}
 - Callback: app\models\Setting::getItems()',
			]); ?>
			<?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? Yii::t('setting', 'Create') : Yii::t('setting', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<div class="modal fade" id="custom-store-range">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= Yii::t('setting', 'Custom Store Range') ?></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-add-new-custom-record"><?= Yii::t('setting', 'Add a new record') ?></button>
				<button type="button" class="btn btn-primary btn-save-custom-record"><?= Yii::t('setting', 'Save changes') ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	$(document).on("change", "#setting-type", function() {
		var th = $(this);
		if(th.val() === 'file_path' || th.val() === 'file_url') {
			$(".store").slideDown();
			$(".icon-setting").hide().parent().removeClass('input-group');
		} else if(th.val() === 'group' || th.val() === 'action') {
			$(".icon-setting").show().parent().addClass('input-group');
			$(".store").slideUp();
		} else {
			$(".store").slideUp();
			$(".icon-setting").hide().parent().removeClass('input-group');
		}
	});
	$('#custom-store-range').on('show.bs.modal', function(e) {
		var th   = $(this);
		var data = $("#setting-store_range").val();
		if(data === '') {
			data = '{}';
		}
		var response = jQuery.parseJSON(data);
		if(typeof response === 'object') {
			var html = '';
			$.each(response, function(k, v) {
				html += '<div class="form-group"><div class="col-sm-5"><input type="text" class="form-control key" placeholder="key" value="' + k + '"></div><div class="col-sm-1"><span class="btn btn-link"><i class="fa fa-arrow-circle-right"></i></span></div><div class="col-sm-5"><input type="text" class="form-control value" placeholder="value" value="' + v + '"></div><button class="btn btn-danger btn-remove-custom-record col-sm-1" type="button"><i class="fa fa-minus"></i></button></div>';
			});
			th.find(".modal-body form").html(html);
		}
		th.on("click", ".btn-add-new-custom-record", function() {
			th.find(".modal-body form").append('<div class="form-group"><div class="col-sm-5"><input type="text" class="form-control key" placeholder="key" value=""></div><div class="col-sm-1"><span class="btn btn-link"><i class="fa fa-arrow-circle-right"></i></span></div><div class="col-sm-5"><input type="text" class="form-control value" placeholder="value" value=""></div><button class="btn btn-danger btn-remove-custom-record col-sm-1" type="button"><i class="fa fa-minus"></i></button></div>');
		});
		th.on("click", ".btn-remove-custom-record", function() {
			$(this).closest(".form-group").remove();
		});
		th.on("click", ".btn-save-custom-record", function() {
			var array = {};
			$.each(th.find(".form-group"), function() {
				var key    = $(this).find('input.key').val();
				array[key] = $(this).find('input.value').val();
			});
			$("#setting-store_range").val(JSON.stringify(array));
			th.modal('hide');
		});
	})
</script>