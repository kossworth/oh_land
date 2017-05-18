<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail_notice".
 *
 * @property integer $id
 * @property string $key
 * @property integer $priority
 * @property string $name
 * @property string $theme_1
 * @property string $theme_2
 * @property string $body_1
 * @property string $body_2
 * @property string $add_recipient
 */
class MailNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name', 'theme_1', 'theme_2', 'body_1', 'body_2', 'add_recipient'], 'required'],
            [['priority'], 'integer'],
            [['body_1', 'body_2'], 'string'],
            [['key'], 'string', 'max' => 32],
            [['name', 'add_recipient'], 'string', 'max' => 128],
            [['theme_1', 'theme_2'], 'string', 'max' => 256],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Системный ключ шаблона'),
            'priority' => Yii::t('app', 'Приоритет при отправке'),
            'name' => Yii::t('app', 'Системное название шаблона'),
            'theme_1' => Yii::t('app', 'Тема письма РУС'),
            'theme_2' => Yii::t('app', 'Тема письма УКР'),
            'body_1' => Yii::t('app', 'Текст письма РУС'),
            'body_2' => Yii::t('app', 'Текст письма УКР'),
            'add_recipient' => Yii::t('app', 'Дополнительные email-адреса'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\MailNoticeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\MailNoticeQuery(get_called_class());
    }
}
