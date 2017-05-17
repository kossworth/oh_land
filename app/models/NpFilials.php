<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "np_filials".
 *
 * @property integer $id
 * @property integer $number
 * @property string $address
 * @property string $phone
 * @property string $worktime
 * @property integer $max_weight
 * @property double $lng
 * @property double $lat
 * @property integer $city_id
 * @property string $city_name
 */
class NpFilials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'np_filials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'address', 'phone', 'lng', 'lat', 'city_id', 'city_name'], 'required'],
            [['number', 'max_weight', 'city_id'], 'integer'],
            [['lng', 'lat'], 'number'],
            [['address', 'worktime'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 128],
            [['city_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number' => Yii::t('app', 'Номер отделения в городе'),
            'address' => Yii::t('app', 'Адрес отделения'),
            'phone' => Yii::t('app', 'Телефоны отделения'),
            'worktime' => Yii::t('app', 'Время работы отделения'),
            'max_weight' => Yii::t('app', 'Максимальный принимаемый вес посылки'),
            'lng' => Yii::t('app', 'Долгота на карте'),
            'lat' => Yii::t('app', 'Широта на карте'),
            'city_id' => Yii::t('app', 'ID города из np_city'),
            'city_name' => Yii::t('app', 'Название города'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\NpFilialsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\NpFilialsQuery(get_called_class());
    }
    
    public static function getAutocompleteFilialsArray($criteria = '', $city_id = 1)
    {
        $search_result = self::find()->where(['LIKE', self::tableName().'.number', $criteria.'%', false])
                ->orWhere(['LIKE', self::tableName().'.address', $criteria.'%', false])
                ->andWhere([self::tableName().'.city_id' => (int)$city_id])
                ->limit(10)
                ->orderBy(self::tableName().'.number ASC')
                ->asArray()
                ->all();
        if(is_null($search_result))
        {
            return ['items' => []];
        }
        
        $filials = [];
        foreach ($search_result as $res)
        {
            $filials['items'][] = ['name' => $res['address'], 'id' => $res['id']];
        }
        return $filials;
    }
}