<?php
namespace navatech\setting\models;

use kartik\password\PasswordInput;
use kartik\widgets\ColorInput;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\RangeInput;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\widgets\TimePicker;
use navatech\language\Translate;
use navatech\roxymce\widgets\RoxyMceWidget;
use navatech\setting\Module;
use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string  $name
 * @property string  $desc
 * @property string  $code
 * @property string  $type
 * @property string  $store_range
 * @property string  $store_dir
 * @property string  $value
 * @property integer $sort_order
 */
class Setting extends ActiveRecord {

	const TYPE_GROUP       = 0;

	const TYPE_TEXT        = 1;

	const TYPE_EMAIL       = 2;

	const TYPE_NUMBER      = 3;

	const TYPE_TEXTAREA    = 4;

	const TYPE_COLOR       = 5;

	const TYPE_DATE        = 6;

	const TYPE_TIME        = 7;

	const TYPE_DATETIME    = 8;

	const TYPE_PASSWORD    = 9;

	const TYPE_ROXYMCE     = 10;

	const TYPE_SELECT      = 11;

	const TYPE_MULTISELECT = 12;

	const TYPE_FILE        = 13;

	const TYPE_PERCENT     = 14;

	const TYPE_SWITCH      = 15;

	const TYPE_CHECKBOX    = 16;

	const TYPE_RADIO       = 17;

	const TYPE             = [
		'group',
		'text',
		'email',
		'number',
		'textarea',
		'color',
		'date',
		'time',
		'datetime',
		'password',
		'roxymce',
		'select',
		'multiselect',
		'file',
		'percent',
		'switch',
		'checkbox',
		'radio',
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%setting}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'parent_id',
					'sort_order',
				],
				'integer',
			],
			[
				[
					'code',
					'type',
				],
				'required',
			],
			[
				['value'],
				'string',
			],
			[
				'type',
				'string',
				'max' => 32,
			],
			[
				[
					'store_range',
					'store_dir',
					'code',
					'name',
					'desc',
				],
				'string',
				'max' => 255,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'          => 'ID',
			'parent_id'   => 'Parent ID',
			'name'        => 'Name',
			'code'        => 'Code',
			'type'        => 'Type',
			'store_range' => 'Store Range',
			'store_dir'   => 'Store Dir',
			'value'       => 'Value',
			'sort_order'  => 'Sort Order',
		];
	}

	/**
	 * @return array
	 */
	public static function getItems() {
		/**@var $parentSettings self[] */
		$items          = [];
		$parentSettings = Setting::find()->where([
			'parent_id' => 0,
			'type'      => self::TYPE_GROUP,
		])->orderBy(['sort_order' => SORT_ASC])->all();
		foreach($parentSettings as $parentSetting) {
			$content = Html::beginForm('', 'POST', ['class' => 'form-horizontal']);
			$content .= $parentSetting->getContent();
			$content .= Html::beginTag('div', ['class' => 'form-group']);
			$content .= Html::beginTag('div', ['class' => 'col-sm-9 col-sm-offset-3']);
			$content .= Html::submitButton('Save', ['class' => 'btn btn-success']);
			$content .= Html::endTag('div');
			$content .= Html::endTag('div');
			$content .= Html::endForm();
			$items[] = [
				'label'   => $parentSetting->name,
				'content' => $content,
			];
		}
		return $items;
	}

	/**
	 * @return string
	 */
	public function getName() {
		if(Module::hasMultiLanguage()) {
			$code = $this->code;
			return Translate::$code();
		} else {
			return $this->name;
		}
	}

	/**
	 * @return string
	 */
	public function getDesc() {
		if(Module::hasMultiLanguage()) {
			$code = 'desc_' . $this->code;
			return Translate::$code();
		} else {
			return $this->desc;
		}
	}

	/**
	 * @return string
	 */
	public function getContent() {
		/**@var $settings self[] */
		$html     = '';
		$settings = $this->find()->where(['parent_id' => $this->id])->orderBy(['sort_order' => SORT_ASC])->all();
		foreach($settings as $setting) {
			$html .= Html::beginTag('div', ['class' => 'form-group']);
			$html .= Html::label($setting->getName(), $setting->code, ['class' => 'col-sm-3 control-label no-padding-right']);
			$html .= Html::beginTag('div', ['class' => 'col-sm-9']);
			$html .= $setting->getType();
			$html .= Html::beginTag('span', ['class' => 'help-block']);
			if(YII_DEBUG && YII_ENV == 'dev') {
				$html .= '<strong>Yii::$app->setting->' . $setting->code . '</strong>.<br/>';
			}
			$html .= $setting->getDesc();
			$html .= Html::endTag('span');
			$html .= Html::endTag('div');
			$html .= Html::endTag('div');
		}
		return $html;
	}

	/**
	 * @param null $options
	 * @param null $pluginOptions
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getType($options = null, $pluginOptions = null) {
		switch($this->type) {
			case self::TYPE_TEXT:
				return Html::input('text', $this->code, $this->value, $options != null ? $options : [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case self::TYPE_EMAIL:
				return Html::input('email', $this->code, $this->value, $options != null ? $options : [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case self::TYPE_NUMBER:
				return Html::input('number', $this->code, $this->value, $options != null ? $options : [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case self::TYPE_TEXTAREA:
				return Html::textarea($this->code, $this->value, $options != null ? $options : [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case self::TYPE_COLOR:
				return ColorInput::widget([
					'id'      => $this->code,
					'value'   => $this->value,
					'name'    => $this->code,
					'options' => $options != null ? $options : [
						'class' => 'form-control',
					],
				]);
			case self::TYPE_DATE:
				return DatePicker::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => $pluginOptions != null ? $pluginOptions : [
						'format'         => 'yyyy-mm-dd',
						'todayHighlight' => true,
					],
				]);
			case self::TYPE_TIME:
				return TimePicker::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => $pluginOptions != null ? $pluginOptions : [
						'minuteStep'   => 1,
						'showSeconds'  => true,
						'showMeridian' => false,
					],
				]);
			case self::TYPE_DATETIME:
				return DateTimePicker::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => [
						'format'         => 'yyyy-mm-dd H:i:s',
						'todayHighlight' => true,
					],
				]);
			case self::TYPE_PASSWORD:
				return PasswordInput::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => $pluginOptions != null ? $pluginOptions : [
						'showMeter'  => true,
						'toggleMask' => false,
					],
				]);
			case self::TYPE_ROXYMCE:
				return RoxyMceWidget::widget([
					'name'        => $this->code,
					'value'       => $this->value,
					'action'      => Url::to(['roxymce/default']),
					'options'     => $options != null ? $options : [
						'title' => $this->getName(),
					],
					'htmlOptions' => $pluginOptions != null ? $pluginOptions : [],
				]);
			case self::TYPE_SELECT:
				return Select2::widget([
					'data'          => implode(",", $this->store_range),
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]);
			case self::TYPE_MULTISELECT:
				$options['multiple'] = true;
				if(!isset($options['class'])) {
					$options['class'] = 'form-control';
				}
				return Select2::widget([
					'data'          => implode(",", $this->store_range),
					'options'       => $options,
					'pluginOptions' => [
						'allowClear' => true,
					],
				]);
			case self::TYPE_FILE:
				return FileInput::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => $pluginOptions != null ? $pluginOptions : [
						'previewFileType' => 'any',
					],
				]);
			case self::TYPE_PERCENT:
				return RangeInput::widget([
					'name'         => $this->code,
					'value'        => $this->value,
					'html5Options' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'options'      => $options != null ? $options : [
						'class' => 'form-control',
					],
					'addon'        => ['append' => ['content' => '%']],
				]);
			case self::TYPE_SWITCH:
				return SwitchInput::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => $options != null ? $options : [
						'class' => 'form-control',
					],
					'pluginOptions' => $pluginOptions != null ? $pluginOptions : [
						'size' => 'small',
					],
				]);
			case self::TYPE_CHECKBOX:
				return true;
			case self::TYPE_RADIO:
				return Html::radioList($this->code, $this->value, implode(',', $this->store_range), $options != null ? $options : [
					'class' => 'form-control',
				]);
			default:
				return Html::input('text', $this->code, $this->value, $options != null ? $options : [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
		}
	}
}
