<?php
use kartik\tabs\TabsX;
use navatech\setting\models\Setting;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var $this  View
 * @var $title string
 */
$this->title                   = $title;
$this->params['breadcrumbs'][] = $this->title;
$items                         = [];
if (YII_DEBUG):
	?>
	<div class="col-sm-offset-9 col-sm-3">
		<div class="form-inline pull-right">
			<?= Html::a('Configure', ['config/index'], ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
<?php endif; ?>
<?= TabsX::widget([
	'items'        => Setting::getItems(),
	'bordered'     => true,
	'position'     => TabsX::POS_ABOVE,
	'encodeLabels' => false,
]); ?>
