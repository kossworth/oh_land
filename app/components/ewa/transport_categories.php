<?php

namespace app\components\ewa;

use yii\db\ActiveRecord;

class transport_categories extends ActiveRecord
{
    public $name, $kind;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transport_categories';
    }
}
