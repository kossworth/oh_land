<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partners".
 *
 * @property integer $id
 * @property string $name_1
 * @property string $host
 * @property string $phone
 * @property string $admin_email
 * @property string $task_email
 * @property integer $crtdate
 */
class Partners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_1', 'host', 'admin_email', 'crtdate'], 'required'],
            [['crtdate'], 'integer'],
            [['name_1', 'host', 'admin_email'], 'string', 'max' => 250],
            [['phone', 'task_email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'name_1'        => Yii::t('app', 'Название'),
            'host'          => Yii::t('app', 'Хост'),
            'phone'         => Yii::t('app', 'Основной телефон партнёра'),
            'admin_email'   => Yii::t('app', 'E-mail с которого отправляется почта'),
            'task_email'    => Yii::t('app', 'E-mail для отправки заявок'),
            'crtdate'       => Yii::t('app', 'Date of creation'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\PartnersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\PartnersQuery(get_called_class());
    }
}
