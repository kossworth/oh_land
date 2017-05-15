<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\NovaposhtaCities]].
 *
 * @see \app\models\NovaposhtaCities
 */
class NovaposhtaCitiesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\NovaposhtaCities[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\NovaposhtaCities|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
