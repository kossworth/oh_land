<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\LandingFeedbacks]].
 *
 * @see \app\models\LandingFeedbacks
 */
class LandingFeedbacksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\LandingFeedbacks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LandingFeedbacks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function published()
    {
        return $this->andWhere(['landing_feedbacks.published' => 1]);
    }
}