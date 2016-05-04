<?php
namespace navatech\setting\models;

use kartik\daterange\DateRangePicker;
use kartik\password\PasswordInput;
use navatech\language\Translate;
use navatech\setting\Module;
use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

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
			'id'          => Module::t('common', 'ID'),
			'parent_id'   => Module::t('common', 'Parent ID'),
			'name'        => Module::t('common', 'Name'),
			'code'        => Module::t('common', 'Code'),
			'type'        => Module::t('common', 'Type'),
			'store_range' => Module::t('common', 'Store Range'),
			'store_dir'   => Module::t('common', 'Store Dir'),
			'value'       => Module::t('common', 'Value'),
			'sort_order'  => Module::t('common', 'Sort Order'),
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
			'type'      => 'group',
		])->orderBy(['sort_order' => SORT_ASC])->all();
		foreach ($parentSettings as $parentSetting) {
			$items[] = [
				'label'   => $parentSetting->name,
				'content' => Html::beginForm('', 'POST', ['class' => 'form-horizontal']) . $parentSetting->getContent() . Html::endForm(),
			];
		}
		return $items;
	}

	/**
	 * @param $attribute
	 *
	 * @return mixed
	 */
	public function getValue($attribute) {
		if (Module::hasMultiLanguage()) {
			$code = $this->$attribute;
			return Translate::$code();
		} else {
			return $this->$attribute;
		}
	}

	public function getName() {
		if (Module::hasMultiLanguage()) {
			$code = $this->code;
			return Translate::$code();
		} else {
			return $this->name;
		}
	}

	public function getDesc() {
		if (Module::hasMultiLanguage()) {
			$code = 'desc_' . $this->code;
			return Translate::$code();
		} else {
			return $this->desc;
		}
	}

	public function getContent() {
		/**@var $settings self[] */
		$html     = '';
		$settings = $this->find()->where(['parent_id' => $this->id])->orderBy(['sort_order' => SORT_ASC])->all();
		foreach ($settings as $setting) {
			$html .= Html::beginTag('div', ['class' => 'form-group']);
			$html .= Html::label($setting->getValue('code'), $setting->code, ['class' => 'col-sm-3 control-label no-padding-right']);
			$html .= Html::beginTag('div', ['class' => 'col-sm-9']);
			$html .= $setting->getType();
			$html .= Html::endTag('div');
			$html .= Html::endTag('div');
		}
		return $html;
	}

	public function getType() {
		//'number','range','textarea','select','multiselect','radio','checkbox','roxymce'
		switch ($this->type) {
			case 'text':
				return Html::input('text', $this->code, $this->value, [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case 'color':
//				return ColorIn
				return Html::input('color', $this->code, $this->value, [
					'class' => 'form-control col-sm-1',
				]);
			case 'date':
				return Html::beginTag('div', ['class' => 'input-group']) . DateRangePicker::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'useWithAddon'  => true,
					'convertFormat' => true,
					'pluginOptions' => [
						'timePicker'       => false,
						'locale'           => ['format' => 'Y-m-d'],
						'singleDatePicker' => true,
						'showDropdowns'    => true,
					],
				]) . Html::beginTag('span', ['class' => 'input-group-addon']) . '<i class="glyphicon glyphicon-calendar"></i>' . Html::endTag('span') . Html::endTag('div');
			case 'datetime':
				return Html::beginTag('div', ['class' => 'input-group']) . DateRangePicker::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'useWithAddon'  => true,
					'convertFormat' => true,
					'pluginOptions' => [
						'timePicker'          => true,
						'timePickerIncrement' => 15,
						'locale'              => ['format' => 'Y-m-d h:i A'],
						'singleDatePicker'    => true,
						'showDropdowns'       => true,
					],
				]) . Html::beginTag('span', ['class' => 'input-group-addon']) . '<i class="glyphicon glyphicon-calendar"></i>' . Html::endTag('span') . Html::endTag('div');
			case 'email':
				return Html::input('email', $this->code, $this->value, [
					'placeholder' => $this->getName(),
					'class'       => 'form-control',
				]);
			case 'password':
				return PasswordInput::widget([
					'id'            => $this->code,
					'name'          => $this->code,
					'value'         => $this->value,
					'options'       => [
						'class' => 'form-control',
					],
					'pluginOptions' => [
						'showMeter'  => true,
						'toggleMask' => false,
					],
				]);
			case 'number':
				return Html::input('number', $this->code, $this->value, [
					'class' => 'form-control',
				]);
			case 'range':
				return Html::input('range', $this->code, $this->value, [
					'class' => 'form-control',
				]);
		}
	}
}
