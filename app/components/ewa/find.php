<?php

namespace app\components\ewa;

use yii\helpers\ArrayHelper;

class find
{
	private static $authorized = NULL;

	/*
	 * Авторизация на сервисе и получение информации об авторизованном пользователе
	 */
	public static function auth($force = FALSE)
	{
		if (!self::$authorized || $force)
		{
			$auth = request::send([
				'action' => 'user/login',
				'post' => [
					'email' => \Yii::$app->params['ewa']['login'],
					'password' => sha1(\Yii::$app->params['ewa']['password'])
				],
			]);

			if ($auth['success'] && $auth['answer_data'])
			{
				self::$authorized = $auth['answer_data'];
			}
		}

		return self::$authorized;
	}

	/*
	 * Возвращает список типов транспортных средств
	 */
	public static function transport_categories($id = NULL)
	{
        $lang = (\Yii::$app->language == 'uk') ? 2 : 1;
		$categories = transport_categories::find()
			->leftJoin('transport_categories_info', '`record_id`=`id` AND `lang`='.$lang)
			->select('`id`, `name`, `kind`, `code`, `engine_volume_max`, `engine_volume_min`')
			->orderBy('`sort` DESC')
			->asArray();

		if ($id > 0)
		{
			$categories = $categories->andWhere(['id' => $id]);
		}

		$categories = $categories->all();
		$categories = ArrayHelper::index($categories, 'id');

		if ($id > 0 && isset($categories[$id]))
		{
			$categories = $categories[$id];
		}

		return $categories;
	}

	/*
	 * Поиск города по названию или части названия.
	 * Если указан $id, метод найдёт
	 */
	public static function city($name, $id = NULL)
	{
		$result = [];

		if (strlen($name) > 2)
		{
			$cities = request::execute([
				'action' => 'place?country=UA&query='.urlencode($name),
			]);

			if (is_array($cities) && count($cities))
			{
				$cities = ArrayHelper::index($cities, 'id');
                $lang = \Yii::$app->language == 'uk' ? '' : 'Rus';
				foreach ($cities as $city)
				{
					$cities[$city['id']] = [
						'id' => $city['id'],
						'name' => $city['name'.$lang],
						'name_full' => $city['nameFull'.$lang],
						'zone' => $city['zone']
					];
				}

				//	Поиск конкретного города по ID
				if ($id > 0)
				{
					if (isset($cities[$id]))
					{
						$result = $cities[$id];
					}
				}
				else
				{
					$result = $cities;
				}
			}
		}

		return $result;
	}

	/*
	 * Выборка всех городов
	 */
	public static function cities()
	{
		return request::execute([
			'action' => 'place/full?country=UA',
		]);
	}

	/*
	 * Выборка всех производителей ТС
	 */
	public static function transport_vendors()
	{
		return request::execute([
			'action' => 'auto_model/makers'
		]);
	}

	/*
	 * Выборка всех моделей ТС выбранного производителя
	 */
	public static function transport_models($vendor_id)
	{
		return request::execute([
			'action' => 'auto_model/models?maker='.(int)$vendor_id
		]);
	}

	/*
	 * Поиск тарифов ОСАГО
	 */
	public static function osago($options)
	{
		$auth = self::auth();
		if(isset($auth['user']['salePoint']['id']))
		{
			$options['salePoint'] = $auth['user']['salePoint']['id'];
		}

		return request::execute([
			'action' => 'tariff/choose/policy',
			'get' => $options
		]);
	}

	/*
	 * Поиск тарифов ДГО
	 */
	public static function dgo($options)
	{
		$auth = self::auth();
		if(isset($auth['user']['salePoint']['id']))
		{
			$options['salePoint'] = $auth['user']['salePoint']['id'];
		}

		return request::execute([
			'action' => 'tariff/choose/vcl',
			'body' => $options
		]);
	}

	/*
	 * Информация о тарифе по ID
	 */
	public static function tariff($id)
	{
		return request::execute([
			'action' => 'tariff/'.(int)$id,
		]);
	}
}