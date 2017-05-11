<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_categories".
 *
 * @property integer $id
 * @property integer $id_auto_kind
 * @property string $name_auto_category
 * @property string $name_auto_category_rus
 * @property string $auto_code
 * @property string $engine_volume_min
 * @property string $engine_volume_max
 * @property integer $order
 */
class AutoCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name_auto_category_rus'], 'required'],
            [['id', 'id_auto_kind', 'order'], 'integer'],
            [['engine_volume_min', 'engine_volume_max'], 'number'],
            [['name_auto_category', 'name_auto_category_rus'], 'string', 'max' => 60],
            [['auto_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_auto_kind' => Yii::t('app', 'Id Auto Kind'),
            'name_auto_category' => Yii::t('app', 'Name Auto Category'),
            'name_auto_category_rus' => Yii::t('app', 'Name Auto Category Rus'),
            'auto_code' => Yii::t('app', 'Auto Code'),
            'engine_volume_min' => Yii::t('app', 'Engine Volume Min'),
            'engine_volume_max' => Yii::t('app', 'Engine Volume Max'),
            'order' => Yii::t('app', 'Порядок сортировки'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\AutoCategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\AutoCategoriesQuery(get_called_class());
    }
}
