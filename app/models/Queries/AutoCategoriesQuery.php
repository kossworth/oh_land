<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\AutoCategories]].
 *
 * @see \app\models\AutoCategories
 */
class AutoCategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\AutoCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\AutoCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
