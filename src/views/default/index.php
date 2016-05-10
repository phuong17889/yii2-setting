<?php
use kartik\tabs\TabsX;
use navatech\base\assets\AwesomeBootstrapCheckboxAsset;
use navatech\setting\models\Setting;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var $this  View
 * @var $title string
 */
AwesomeBootstrapCheckboxAsset::register($this);
$this->title                   = $title;
$this->params['breadcrumbs'][] = $this->title;
$items                         = [];
if (Yii::$app->session->hasFlash('alert')) {
	echo Alert::widget(Yii::$app->session->getFlash('alert'));
}
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
