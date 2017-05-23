<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property integer $id
 * @property string $name_1
 * @property string $name_2
 * @property string $alias
 * @property string $text_1
 * @property string $text_2
 * @property integer $to_main
 * @property integer $sort
 * @property integer $crtdate
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'name_2', 'text_1', 'text_2', 'sort', 'crtdate'], 'required'],
            [['text_1', 'text_2'], 'string'],
            [['to_main', 'to_landing', 'sort', 'crtdate'], 'integer'],
            [['name_1', 'name_2'], 'string', 'max' => 250],
            [['alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_1' => Yii::t('app', 'Заголовок'),
            'name_2' => Yii::t('app', 'Заголовок УКР'),
            'alias' => Yii::t('app', 'Alias'),
            'text_1' => Yii::t('app', 'Текст'),
            'text_2' => Yii::t('app', 'Текст УКР'),
            'to_main' => Yii::t('app', 'To Main'),
            'to_landing' => Yii::t('app', 'To Landing'),
            'sort' => Yii::t('app', 'SORT'),
            'crtdate' => Yii::t('app', 'Date of creation'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\FaqQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\FaqQuery(get_called_class());
    }
}
