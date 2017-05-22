<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "landing_reasons".
 *
 * @property integer $id
 * @property integer $name_1
 * @property integer $name_2
 * @property integer $active
 * @property integer $sort
 * @property integer $crtdate
 */
class LandingReasons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'landing_reasons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'crtdate'], 'required'],
            [['active', 'sort', 'crtdate'], 'integer'],
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
            'active' => Yii::t('app', 'Active'),
            'sort' => Yii::t('app', 'Sort'),
            'crtdate' => Yii::t('app', 'Crtdate'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\LandingReasonsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\LandingReasonsQuery(get_called_class());
    }
}
