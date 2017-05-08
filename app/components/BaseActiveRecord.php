<?php

namespace app\components;

use Yii;

/**
 * This is the base model class for tables.
 *

 */
class BaseActiveRecord extends \yii\db\ActiveRecord
{
	public function getPubDate($pub_date,$format)
	{
		$date=\DateTime::createFromFormat('Y-m-d',$pub_date);
		return $date->format($format);
	}
}