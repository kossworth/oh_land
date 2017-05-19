<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\Faq]].
 *
 * @see \app\models\Faq
 */
class FaqQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Faq[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Faq|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
