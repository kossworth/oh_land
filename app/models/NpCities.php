<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "np_cities".
 *
 * @property integer $id
 * @property string $name_1
 * @property string $name_2
 * @property string $region_name
 * @property integer $parent_id
 */
class NpCities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'np_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'name_2', 'region_name', 'parent_id'], 'required'],
            [['parent_id'], 'integer'],
            [['name_1', 'name_2', 'region_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'name_1'        => Yii::t('app', 'Название города РУС'),
            'name_2'        => Yii::t('app', 'Название города УКР'),
            'region_name'   => Yii::t('app', 'Название области'),
            'parent_id'     => Yii::t('app', 'Привязка к области'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\NpCitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\NpCitiesQuery(get_called_class());
    }
    
    public static function getAutocompleteArray($criteria)
    {
        $search_result = self::find()->where(['LIKE', self::tableName().'.name_ru', $criteria.'%', false])
                ->orWhere(['LIKE', self::tableName().'.name_ua', $criteria.'%', false])
                ->limit(10)->orderBy(self::tableName().'.name_ru ASC')
                ->asArray()
                ->all();
        
        if(is_null($search_result))
        {
            return false;
        }
        
        $cities = [];
        foreach ($search_result as $res)
        {
            $cities['items'][] = ['name' => $res['name_ru'], 'id' => $res['id']];
        }
        return $cities;
    }
}
