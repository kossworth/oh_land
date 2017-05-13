<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name_1
 * @property string $name_2
 * @property string $alias
 * @property integer $active
 * @property integer $active_osago
 * @property integer $active_greencard
 * @property string $description_1
 * @property string $description_2
 * @property double $cis_id
 * @property integer $ewa_id
 * @property double $rating
 * @property string $background_file
 * @property integer $sort
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'description_1', 'description_2', 'cis_id', 'ewa_id', 'rating', 'sort'], 'required'],
            [['active', 'active_osago', 'active_greencard', 'ewa_id', 'sort'], 'integer'],
            [['description_1', 'description_2'], 'string'],
            [['cis_id', 'rating'], 'number'],
            [['name_1'], 'string', 'max' => 100],
            [['name_2', 'alias', 'background_file'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_1' => Yii::t('app', 'Name 1'),
            'name_2' => Yii::t('app', 'Name 2'),
            'alias' => Yii::t('app', 'Alias'),
            'active' => Yii::t('app', 'Active'),
            'active_osago' => Yii::t('app', 'Active Osago'),
            'active_greencard' => Yii::t('app', 'Active Greencard'),
            'description_1' => Yii::t('app', 'Description 1'),
            'description_2' => Yii::t('app', 'Description 2'),
            'cis_id' => Yii::t('app', 'CIS ID'),
            'ewa_id' => Yii::t('app', 'EWA ID'),
            'rating' => Yii::t('app', 'Рейтинг'),
            'background_file' => Yii::t('app', 'Background File'),
            'sort' => Yii::t('app', 'SORT'),
        ];
    }

    public function getOsago()
    {
        return $this->hasOne(CompanyProduct::className(), ['company_id' => 'id'])
                ->andWhere([CompanyProduct::tableName().'.product_id' => CompanyProduct::TYPE_OSAGO]);
    }

    public function getTravel()
    {
        return $this->hasOne(CompanyProduct::className(), ['company_id' => 'id'])
                ->andWhere([CompanyProduct::tableName().'.product_id' => CompanyProduct::TYPE_TRAVEL]);
    }

    public function getGreencard()
    {
        return $this->hasOne(CompanyProduct::className(), ['company_id' => 'id'])
                ->andWhere([CompanyProduct::tableName().'.product_id' => CompanyProduct::TYPE_GREENCARD]);
    }

    public function getProducts()
    {
        return $this->hasMany(CompanyProduct::className(), ['company_id' => 'id']);
    }

    public function getLogo()
    {
//        $path = __DIR__ . "/../../../images/company/{$this->id}.2.b.jpg";
//        return $path;
//        if(file_exists($path))
//        {
            return '/images/company/'.$this->id.'.1.b.png';
//        }
//        else
//        {
//            return null;
//        }
    }
    
    /**
     * @inheritdoc
     * @return \app\models\Queries\CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\CompanyQuery(get_called_class());
    }
}
