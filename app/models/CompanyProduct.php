<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_product".
 *
 * @property integer $id
 * @property string $name_1
 * @property string $name_2
 * @property integer $active
 * @property string $description_1
 * @property string $description_2
 * @property integer $company_id
 * @property integer $product_id
 * @property integer $landing_top
 * @property integer $landing_recommend
 * @property string $landing_bonuses
 */
class CompanyProduct extends \yii\db\ActiveRecord
{
    const TYPE_OSAGO            = 1;
    const TYPE_TRAVEL           = 2;
    const TYPE_GREENCARD        = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'name_2', 'description_1', 'description_2', 'company_id', 'product_id'], 'required'],
            [['active', 'company_id', 'product_id', 'landing_top', 'landing_recommend'], 'integer'],
            [['description_1', 'description_2', 'landing_bonuses'], 'string'],
            [['name_1', 'name_2'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_1' => Yii::t('app', 'Индивидуальное название'),
            'name_2' => Yii::t('app', 'Индивидуальное название УКР'),
            'active' => Yii::t('app', 'Active'),
            'description_1' => Yii::t('app', 'Описание РУС'),
            'description_2' => Yii::t('app', 'Описание УКР'),
            'company_id' => Yii::t('app', 'Компания'),
            'product_id' => Yii::t('app', 'Тип продукта'),
            'landing_top' => Yii::t('app', 'Белый логотип компании'),
            'landing_recommend' => Yii::t('app', 'Landing Recommend'),
            'landing_bonuses' => Yii::t('app', 'Бонусы предложенея'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\CompanyProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\CompanyProductQuery(get_called_class());
    }
}
