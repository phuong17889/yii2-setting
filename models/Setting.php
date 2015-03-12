<?php

namespace itzen\setting\models;

use Yii;
use itzen\setting\Module;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string $store_range
 * @property string $store_dir
 * @property string $value
 * @property integer $sort_order
 */
class Setting extends \yii\db\ActiveRecord
{
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
            [['parent_id', 'sort_order'], 'integer'],
            [['code', 'type'], 'required'],
            [['value'], 'string'],
            ['type', 'string', 'max' => 32],
            [['store_range', 'store_dir','code', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('common', 'ID'),
            'parent_id' => Module::t('common', 'Parent ID'),
            'name' => Module::t('common', 'Name'),
            'code' => Module::t('common', 'Code'),
            'type' => Module::t('common', 'Type'),
            'store_range' => Module::t('common', 'Store Range'),
            'store_dir' => Module::t('common', 'Store Dir'),
            'value' => Module::t('common', 'Value'),
            'sort_order' => Module::t('common', 'Sort Order'),
        ];
    }
}
