<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\AutoModels]].
 *
 * @see \app\models\AutoModels
 */
class AutoModelsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\AutoModels[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\AutoModels|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
