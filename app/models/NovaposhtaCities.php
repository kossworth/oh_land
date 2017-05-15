<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novaposhta_cities".
 *
 * @property integer $id
 * @property string $ref_id
 * @property string $area_ref_id
 * @property string $name_ru
 * @property string $name_ua
 */
class NovaposhtaCities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novaposhta_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref_id', 'area_ref_id', 'name_ru', 'name_ua'], 'required'],
            [['ref_id', 'area_ref_id'], 'string', 'max' => 128],
            [['name_ru', 'name_ua'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'ref_id'            => Yii::t('app', 'Ref ID'),
            'area_ref_id'       => Yii::t('app', 'Area Ref ID'),
            'name_ru'           => Yii::t('app', 'Name Ru'),
            'name_ua'           => Yii::t('app', 'Name Ua'),
        ];
    }

    public function getFilials()
    {
        return $this->hasMany(NovaposhtaFilials::className(), [NovaposhtaFilials::tableName().'.city_id' => self::tableName().'.id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\Queries\NovaposhtaCitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\NovaposhtaCitiesQuery(get_called_class());
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
