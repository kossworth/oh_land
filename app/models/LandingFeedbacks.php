<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "landing_feedbacks".
 *
 * @property integer $id
 * @property string $username
 * @property string $userphone
 * @property string $text
 * @property integer $level_companies
 * @property integer $level_service
 * @property integer $level_price
 * @property integer $processed
 * @property integer $published
 * @property integer $crtdate
 */
class LandingFeedbacks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'landing_feedbacks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'userphone', 'text', 'crtdate'], 'required'],
            [['username', 'text'], 'string'],
            [['level_companies', 'level_service', 'level_price', 'processed', 'published', 'crtdate'], 'integer'],
            [['userphone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'userphone' => Yii::t('app', 'Userphone'),
            'text' => Yii::t('app', 'Text'),
            'level_companies' => Yii::t('app', 'Выбор компаний'),
            'level_service' => Yii::t('app', 'Качество сервиса'),
            'level_price' => Yii::t('app', 'Уровень цен'),
            'processed' => Yii::t('app', 'Обработан'),
            'published' => Yii::t('app', 'Опубликован'),
            'crtdate' => Yii::t('app', 'Crtdate'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\LandingFeedbacksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\LandingFeedbacksQuery(get_called_class());
    }
}
