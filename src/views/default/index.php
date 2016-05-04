<?php
use kartik\tabs\TabsX;
use navatech\setting\models\Setting;
use yii\web\View;

/**
 * @var $this  View
 * @var $title string
 */
$this->title                   = $title;
$this->params['breadcrumbs'][] = $this->title;
$items                         = [];
?>
<div class="col-sm-offset-9 col-sm-3">
	<div class="form-inline pull-right">
		<label class="control-label">Edit mode</label>
		<?php
		echo \kartik\widgets\SwitchInput::widget([
			'name'          => 'edit_mode',
			'pluginOptions' => [
				'size' => 'small',
			],
		])
		?>
	</div>
</div>
<?php
echo TabsX::widget([
	'items'        => Setting::getItems(),
	'bordered'     => true,
	'position'     => TabsX::POS_ABOVE,
	'encodeLabels' => false,
]);
?>
