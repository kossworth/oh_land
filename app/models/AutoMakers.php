<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_makers".
 *
 * @property integer $id_auto_maker
 * @property string $name_auto_maker
 * @property integer $id_auto_maker_mtsbu
 *
 * @property AutoModels[] $autoModels
 */
class AutoMakers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_makers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_auto_maker'], 'required'],
            [['id_auto_maker', 'id_auto_maker_mtsbu'], 'integer'],
            [['name_auto_maker'], 'string', 'max' => 20],
            [['id_auto_maker', 'id_auto_maker_mtsbu'], 'unique', 'targetAttribute' => ['id_auto_maker', 'id_auto_maker_mtsbu'], 'message' => 'The combination of Домен для полей-идентификаторов линейных справочников and Идентификатор производителя автомобиля из справочника МТСБУ has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_auto_maker' => Yii::t('app', 'Домен для полей-идентификаторов линейных справочников'),
            'name_auto_maker' => Yii::t('app', 'Наименование производителя'),
            'id_auto_maker_mtsbu' => Yii::t('app', 'Идентификатор производителя автомобиля из справочника МТСБУ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoModels()
    {
        return $this->hasMany(AutoModels::className(), ['id_auto_maker' => 'ID_AUTO_MAKER']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\AutoMakersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\AutoMakersQuery(get_called_class());
    }
    
    public static function getAutocompleteMakersArray($criteria)
    {
        $search_result = self::find()->where(['LIKE', self::tableName().'.name_auto_maker', $criteria.'%', false])
                ->limit(10)
                ->orderBy(self::tableName().'.name_auto_maker ASC')
                ->asArray()
                ->all();
        
        if(is_null($search_result))
        {
            return false;
        }
        
        $items = [];
        foreach ($search_result as $res)
        {
            $items['items'][] = ['name' => $res['name_auto_maker'], 'id' => $res['id_auto_maker']];
        }
        return $items;
    }
}
