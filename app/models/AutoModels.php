<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_models".
 *
 * @property integer $id_auto_models
 * @property integer $id_auto_maker
 * @property integer $id_auto_type
 * @property integer $id_auto_category
 * @property string $name_auto_model
 * @property integer $is_accepted
 * @property resource $note
 * @property integer $id_auto_models_mtsbu
 * @property integer $id_uto_kind
 * @property integer $id_auto_capacity
 * @property integer $id_country
 * @property integer $cata1
 * @property integer $cata2
 * @property integer $catb1
 * @property integer $catb2
 * @property integer $catb3
 * @property integer $catb4
 * @property integer $catc1
 * @property integer $catc2
 * @property integer $catd1
 * @property integer $catd2
 * @property integer $cate
 * @property integer $catf
 *
 * @property AutoMakers $idAutoMaker
 */
class AutoModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_models';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_auto_models'], 'required'],
            [['id_auto_models', 'id_auto_maker', 'id_auto_type', 'id_auto_category', 'is_accepted', 'id_auto_models_mtsbu', 'id_uto_kind', 'id_auto_capacity', 'id_country', 'cata1', 'cata2', 'catb1', 'catb2', 'catb3', 'catb4', 'catc1', 'catc2', 'catd1', 'catd2', 'cate', 'catf'], 'integer'],
            [['note'], 'string'],
            [['name_auto_model'], 'string', 'max' => 50],
            [['id_auto_models', 'id_auto_models_mtsbu'], 'unique', 'targetAttribute' => ['id_auto_models', 'id_auto_models_mtsbu'], 'message' => 'The combination of Домен для полей-идентификаторов линейных справочников and Идентификатор модели автомобиля из справочника МТСБУ has already been taken.'],
            [['id_auto_maker'], 'exist', 'skipOnError' => true, 'targetClass' => AutoMakers::className(), 'targetAttribute' => ['id_auto_maker' => 'ID_AUTO_MAKER']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_auto_models' => Yii::t('app', 'Домен для полей-идентификаторов линейных справочников'),
            'id_auto_maker' => Yii::t('app', 'Производитель модели'),
            'id_auto_type' => Yii::t('app', 'Тип транспортного средства'),
            'id_auto_category' => Yii::t('app', 'Категория'),
            'name_auto_model' => Yii::t('app', 'Наименование модели'),
            'is_accepted' => Yii::t('app', 'Признак утверждения модели (0-нет, 1-да)'),
            'note' => Yii::t('app', 'Примечание'),
            'id_auto_models_mtsbu' => Yii::t('app', 'Идентификатор модели автомобиля из справочника МТСБУ'),
            'id_uto_kind' => Yii::t('app', 'Идентификатор вида автомобильных транспортных средств'),
            'id_auto_capacity' => Yii::t('app', '[Удалить. Не используется] Идентфикатор вместительности автомобильных транспортных средств'),
            'id_country' => Yii::t('app', 'Идентификатор страны-производителя (в будущем), а пока признак: 0 - СНГ, 1 - иностранный производитель'),
            'cata1' => Yii::t('app', 'Категория A1'),
            'cata2' => Yii::t('app', 'Категория A2'),
            'catb1' => Yii::t('app', 'Категория B1'),
            'catb2' => Yii::t('app', 'Категория B2'),
            'catb3' => Yii::t('app', 'Категория B3'),
            'catb4' => Yii::t('app', 'Категория B4'),
            'catc1' => Yii::t('app', 'Категория C1'),
            'catc2' => Yii::t('app', 'Категория C2'),
            'catd1' => Yii::t('app', 'Категория D1'),
            'catd2' => Yii::t('app', 'Категория D2'),
            'cate' => Yii::t('app', 'Категория E'),
            'catf' => Yii::t('app', 'Категория F'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAutoMaker()
    {
        return $this->hasOne(AutoMakers::className(), ['ID_AUTO_MAKER' => 'id_auto_maker']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\AutoModelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\AutoModelsQuery(get_called_class());
    }
    
    public static function getAutocompleteModelsArray($criteria, $brand_id = null)
    {
        $search_result = self::find()->where(['LIKE', self::tableName().'.name_auto_model', $criteria.'%', false])
                ->andFilterWhere([self::tableName().'.id_auto_maker' => $brand_id])
                ->limit(10)
                ->orderBy(self::tableName().'.name_auto_model ASC')
                ->asArray()
                ->all();
        
        if(is_null($search_result))
        {
            return false;
        }
        
        $items = [];
        foreach ($search_result as $res)
        {
            $items['items'][] = ['name' => $res['name_auto_model'], 'id' => $res['id_auto_models']];
        }
        return $items;
    }
}
