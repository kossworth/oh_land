<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novaposhta_filials".
 *
 * @property integer $id
 * @property string $ref_id
 * @property integer $number
 * @property string $address
 * @property string $phone
 * @property string $worktime
 * @property integer $max_weight
 * @property double $lng
 * @property double $lat
 * @property integer $city_id
 */
class NovaposhtaFilials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novaposhta_filials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref_id', 'number', 'address', 'phone', 'lng', 'lat', 'city_id'], 'required'],
            [['number', 'max_weight', 'city_id'], 'integer'],
            [['lng', 'lat'], 'number'],
            [['ref_id', 'phone'], 'string', 'max' => 128],
            [['address', 'worktime'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref_id' => Yii::t('app', 'Ref ID'),
            'number' => Yii::t('app', 'Номер отделения в городе'),
            'address' => Yii::t('app', 'Адрес отделения'),
            'phone' => Yii::t('app', 'Телефоны отделения'),
            'worktime' => Yii::t('app', 'Время работы отделения'),
            'max_weight' => Yii::t('app', 'Максимальный принимаемый вес посылки'),
            'lng' => Yii::t('app', 'Долгота на карте'),
            'lat' => Yii::t('app', 'Широта на карте'),
            'city_id' => Yii::t('app', 'ID города из np_city'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\NovaposhtaFilialsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\NovaposhtaFilialsQuery(get_called_class());
    }
}
