<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\Lang]].
 *
 * @see \app\models\Lang
 */
class Lang extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Lang[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Lang|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDefault()
    {
        return parent::where('`by_default` = :default', [':default' => 1])->limit(1)->one();
    }

    /**
     * @inheritdoc
     * @param $url
     * @return array|null|\yii\db\ActiveRecord
     */
    public function byUrl($url)
    {
        return parent::where('url = :url', [':url' => $url])->limit(1)->one();
    }

    public function allWithoutCurrent($current_id)
    {
        return parent::where('id != :current_id', [':current_id' => $current_id])->all();
    }
}

