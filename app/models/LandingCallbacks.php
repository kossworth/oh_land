<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "landing_callbacks".
 *
 * @property integer $id
 * @property string $phone
 * @property integer $processed
 * @property integer $crtdate
 */
class LandingCallbacks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'landing_callbacks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'crtdate'], 'required'],
            [['processed', 'crtdate'], 'integer'],
            [['phone'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'phone' => Yii::t('app', 'Phone'),
            'processed' => Yii::t('app', 'Processed'),
            'crtdate' => Yii::t('app', 'Crtdate'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\LandingCallbacksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\LandingCallbacksQuery(get_called_class());
    }
}
