<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\LandingCallbacks]].
 *
 * @see \app\models\LandingCallbacks
 */
class LandingCallbacksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\LandingCallbacks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LandingCallbacks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
