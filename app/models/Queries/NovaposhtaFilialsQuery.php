<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\NovaposhtaFilials]].
 *
 * @see \app\models\NovaposhtaFilials
 */
class NovaposhtaFilialsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\NovaposhtaFilials[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\NovaposhtaFilials|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
