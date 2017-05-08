<?php

namespace app\components\ewa;

use app\models\Orders;
use yii\helpers\ArrayHelper;

class save
{
	/*
	 * Сохранение договора ОСАГО
	 */
	public static function osago(Orders $order)
	{
		$auth = find::auth();
		$search = $order->getSearchData();
		$offer = $order->getOfferData();
		$info = $order->getInfoData();

		$contract['send'] = [
			'type'            => 'policy',
			'salePoint'       => [
				'id'      => ArrayHelper::getValue($auth, 'user.salePoint.id'),	//	897
				'company' => [
					'type' => 'broker',
					'id'   => ArrayHelper::getValue($auth, 'user.salePoint.company.id'),	//	9
				],
			],
			'user'            => [
				'id' => ArrayHelper::getValue($auth, 'user.id'),	//	815
			],
			'tariff'          => [
				'type' => 'policy',
				'id'   => ArrayHelper::getValue($offer, 'osago.tariff.id'),	//	1556
			],
			'date'            => date('Y-m-d'),	//	2016-10-03
			'dateFrom'        => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'contract.start_date'))->format('Y-m-d'),	//	2016-10-03T21:00:00.000+0000
			'dateTo'          => NULL,	//	2017-10-03
			'customer'        => [
				'code'         => ArrayHelper::getValue($info, 'customer.code'),	//	0000048701
				'dontHaveCode' => FALSE,
				'name'         => ArrayHelper::getValue($info, 'customer.name_last').' '.ArrayHelper::getValue($info, 'customer.name_first').' '.ArrayHelper::getValue($info, 'name_customer.name_middle'),	//	Иванов Иван Иванович
				'nameLast'     => ArrayHelper::getValue($info, 'customer.name_last'),	//	Иванов
				'nameFirst'    => ArrayHelper::getValue($info, 'customer.name_first'),	//	Иван
				'nameMiddle'   => ArrayHelper::getValue($info, 'customer.name_middle'),	//	Иванович
				'address'      => ArrayHelper::getValue($info, 'customer.address'),	//	03150, м. Київ, вул. Ямська, буд. 487
				'phone'        => preg_replace('#[^\d\+]+#', '', ArrayHelper::getValue($info, 'customer.phone')),	//	+380670048701
				'birthDate'    => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'customer.birth_date'))->format('Y-m-d'),	//	1976-04-03
				'document'     => [
					'type'     => ArrayHelper::getValue($info, 'document.type'),
					'series'   => ArrayHelper::getValue($info, 'document.series'),	//	АА
					'number'   => ArrayHelper::getValue($info, 'document.number'),	//	048701
					'date'     => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'document.issued_date'))->format('Y-m-d'),	//	1994-12-09
					'issuedBy' => ArrayHelper::getValue($info, 'document.issued_by'),	//	ДАІ МВС-УВС
				],
				'legal'        => FALSE,
			],
			'insuranceObject' => [
				'type'              => 'auto',
				'category'          => ArrayHelper::getValue($search, 'car_category.code'),	//	B1
				'model'             => [
					'id'        => ArrayHelper::getValue($info, 'transport.model_id'),	//	5811
					'autoMaker' => [
						'id' => ArrayHelper::getValue($info, 'transport.vendor_id'),	//	161
					],
				],
				'modelText'         => ArrayHelper::getValue($info, 'transport.vendor').' '.ArrayHelper::getValue($info, 'transport.model'),	//	Geely CK
				'bodyNumber'        => mb_strtoupper(ArrayHelper::getValue($info, 'transport.vin_number'), 'UTF-8'),	//	AAAAAAAAAAAA42076
				'stateNumber'       => mb_strtoupper(ArrayHelper::getValue($info, 'transport.gov_number'), 'UTF-8'),	//	ЯЯ4207ЯЯ
				'registrationPlace' => [
					'id'   => ArrayHelper::getValue($search, 'city.id'),	//	1
					'zone' => ArrayHelper::getValue($search, 'city.zone'),	//	1
				],
				'year'              => ArrayHelper::getValue($info, 'transport.year'),	//	2008
				'registrationType'	=> 'PERMANENT_WITHOUT_OTK',
				'otkDate' 			=> '',
			],
			'state'           => 'DRAFT',
			'bonusMalus'      => 0.8,	//	0.8
			'notes' => ArrayHelper::getValue($info, 'contract.comment'),
			'number' => "АВ ".substr(time(), -7, 7),
			'stickerNumber' => "АВ ".substr(time(), -7, 7),
		];

		//	Преобразование дат
		$contract['send']['dateTo'] = date('Y-m-d', strtotime($contract['send']['dateFrom'].' +1 year -1 day'));
		$contract['send']['dateFrom'] = gmdate('Y-m-d\TH:i:s.000+0000', strtotime($contract['send']['dateFrom']));

		$contract['answer'] = request::execute([
			'action' => 'contract/save',
			'body' => $contract['send'],
		]);

		return $contract;
	}

	/*
	 * Сохранение договора ДГО
	 */
	public static function dgo(Orders $order)
	{
		$contract = [
			'send' => NULL,
			'answer' => NULL
		];

		$auth = find::auth();
		$search = $order->getSearchData();
		$offer = $order->getOfferData();
		$info = $order->getInfoData();

		if (isset($offer['dgo']['tariff']['id']))
		{
			$contract['send'] = [
				'type'            => 'vcl',
				'limit'			  => ArrayHelper::getValue($offer, 'dgo.limit'),
				'baseTariff'	  => 250,
				'payment'			  => ArrayHelper::getValue($offer, 'dgo.payment'),
				'salePoint'       => [
					'id'      => ArrayHelper::getValue($auth, 'user.salePoint.id'),	//	897
					'company' => [
						'type' => 'broker',
						'id'   => ArrayHelper::getValue($auth, 'user.salePoint.company.id'),	//	9
					],
				],
				'user'            => [
					'id' => ArrayHelper::getValue($auth, 'user.id'),	//	815
				],
				'tariff'          => [
					'type' => 'vcl',
					'id'   => ArrayHelper::getValue($offer, 'dgo.tariff.id'),	//	1556
				],
				'date'            => date('Y-m-d'),	//	2016-10-03
				'dateFrom'        => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'contract.start_date'))->format('Y-m-d'),	//	2016-10-03T21:00:00.000+0000
				'dateTo'          => NULL,	//	2017-10-03
				'customer'        => [
					'code'         => ArrayHelper::getValue($info, 'customer.code'),	//	0000048701
					'dontHaveCode' => FALSE,
					'name'         => ArrayHelper::getValue($info, 'customer.name_last').' '.ArrayHelper::getValue($info, 'customer.name_first').' '.ArrayHelper::getValue($info, 'name_customer.name_middle'),	//	Иванов Иван Иванович
					'nameLast'     => ArrayHelper::getValue($info, 'customer.name_last'),	//	Иванов
					'nameFirst'    => ArrayHelper::getValue($info, 'customer.name_first'),	//	Иван
					'nameMiddle'   => ArrayHelper::getValue($info, 'customer.name_middle'),	//	Иванович
					'address'      => ArrayHelper::getValue($info, 'customer.address'),	//	03150, м. Київ, вул. Ямська, буд. 487
					'phone'        => preg_replace('#[^\d\+]+#', '', ArrayHelper::getValue($info, 'customer.phone')),	//	+380670048701
					'birthDate'    => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'customer.birth_date'))->format('Y-m-d'),	//	1976-04-03
					'document'     => [
						'type'     => ArrayHelper::getValue($info, 'document.type'),
						'series'   => ArrayHelper::getValue($info, 'document.series'),	//	АА
						'number'   => ArrayHelper::getValue($info, 'document.number'),	//	048701
						'date'     => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'document.issued_date'))->format('Y-m-d'),	//	1994-12-09
						'issuedBy' => ArrayHelper::getValue($info, 'document.issued_by'),	//	ДАІ МВС-УВС
					],
					'legal'        => FALSE,
				],
				'insuranceObject' => [
					'type'              => 'auto',
					'category'          => ArrayHelper::getValue($search, 'car_category.code'),	//	B1
					'model'             => [
						'id'        => ArrayHelper::getValue($info, 'transport.model_id'),	//	5811
						'autoMaker' => [
							'id' => ArrayHelper::getValue($info, 'transport.vendor_id'),	//	161
						],
					],
					'modelText'         => ArrayHelper::getValue($info, 'transport.vendor').' '.ArrayHelper::getValue($info, 'transport.model'),	//	Geely CK
					'bodyNumber'        => mb_strtoupper(ArrayHelper::getValue($info, 'transport.vin_number'), 'UTF-8'),	//	AAAAAAAAAAAA42076
					'stateNumber'       => mb_strtoupper(ArrayHelper::getValue($info, 'transport.gov_number'), 'UTF-8'),	//	ЯЯ4207ЯЯ
					'registrationPlace' => [
						'id'   => ArrayHelper::getValue($search, 'city.id'),	//	1
						'zone' => ArrayHelper::getValue($search, 'city.zone'),	//	1
					],
					'year'              => ArrayHelper::getValue($info, 'transport.year'),	//	2008
					'registrationType'	=> 'PERMANENT_WITHOUT_OTK',
					'otkDate' 			=> '',
				],
				'state'           => 'DRAFT',
				'notes' => ArrayHelper::getValue($info, 'contract.comment'),
				'number' => "АВ ".substr(time(), -6, 6),
				'stickerNumber' => "АВ ".substr(time(), -6, 6),
			];

			//	Преобразование дат
			$contract['send']['dateTo'] = date('Y-m-d', strtotime($contract['send']['dateFrom'].' +1 year -1 day'));
			$contract['send']['dateFrom'] = gmdate('Y-m-d\TH:i:s.000+0000', strtotime($contract['send']['dateFrom']));

			$contract['answer'] = request::execute([
				'action' => 'contract/save',
				'body' => $contract['send'],
			]);
		}

		return $contract;
	}


	/*
	 * Сохранение договора Автогражданка
	 */
	public static function auto(Orders $order, $osago_number)
	{
		$contract = [
			'send' => NULL,
			'answer' => NULL
		];

		$auth = find::auth();
		$search = $order->getSearchData();
		$offer = $order->getOfferData();
		$info = $order->getInfoData();

		if (isset($offer['auto']['tariff']['id']))
		{
			$contract['send'] = [
				'type'            => 'custom',
				'salePoint'       => [
					'id'      => ArrayHelper::getValue($auth, 'user.salePoint.id'),	//	897
					'company' => [
						'type' => 'broker',
						'id'   => ArrayHelper::getValue($auth, 'user.salePoint.company.id'),	//	9
					],
				],
				'user'            => [
					'id' => ArrayHelper::getValue($auth, 'user.id'),	//	815
				],
				'tariff'          => [
					'type' => 'custom',
					'id'   => ArrayHelper::getValue($offer, 'auto.tariff.id'),	//	1556
				],
				'date'            => date('Y-m-d'),	//	2016-10-03
				'dateFrom'        => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'contract.start_date'))->format('Y-m-d'),	//	2016-10-03T21:00:00.000+0000
				'dateTo'          => NULL,	//	2017-10-03
				'customer'        => [
					'code'         => ArrayHelper::getValue($info, 'customer.code'),	//	0000048701
					'dontHaveCode' => FALSE,
					'name'         => ArrayHelper::getValue($info, 'customer.name_last').' '.ArrayHelper::getValue($info, 'customer.name_first').' '.ArrayHelper::getValue($info, 'name_customer.name_middle'),	//	Иванов Иван Иванович
					'nameLast'     => ArrayHelper::getValue($info, 'customer.name_last'),	//	Иванов
					'nameFirst'    => ArrayHelper::getValue($info, 'customer.name_first'),	//	Иван
					'nameMiddle'   => ArrayHelper::getValue($info, 'customer.name_middle'),	//	Иванович
					'address'      => ArrayHelper::getValue($info, 'customer.address'),	//	03150, м. Київ, вул. Ямська, буд. 487
					'phone'        => preg_replace('#[^\d\+]+#', '', ArrayHelper::getValue($info, 'customer.phone')),	//	+380670048701
					'birthDate'    => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'customer.birth_date'))->format('Y-m-d'),	//	1976-04-03
					'document'     => [
						'type'     => ArrayHelper::getValue($info, 'document.type'),
						'series'   => ArrayHelper::getValue($info, 'document.series'),	//	АА
						'number'   => ArrayHelper::getValue($info, 'document.number'),	//	048701
						'date'     => date_create_from_format('d.m.Y', ArrayHelper::getValue($info, 'document.issued_date'))->format('Y-m-d'),	//	1994-12-09
						'issuedBy' => ArrayHelper::getValue($info, 'document.issued_by'),	//	ДАІ МВС-УВС
					],
					'legal'        => FALSE,
				],
				'insuranceObject' => [
					'type'              => 'auto',
					'category'          => ArrayHelper::getValue($search, 'car_category.code'),	//	B1
					'model'             => [
						'id'        => ArrayHelper::getValue($info, 'transport.model_id'),	//	5811
						'autoMaker' => [
							'id' => ArrayHelper::getValue($info, 'transport.vendor_id'),	//	161
						],
					],
					'modelText'         => ArrayHelper::getValue($info, 'transport.vendor').' '.ArrayHelper::getValue($info, 'transport.model'),	//	Geely CK
					'bodyNumber'        => mb_strtoupper(ArrayHelper::getValue($info, 'transport.vin_number'), 'UTF-8'),	//	AAAAAAAAAAAA42076
					'stateNumber'       => mb_strtoupper(ArrayHelper::getValue($info, 'transport.gov_number'), 'UTF-8'),	//	ЯЯ4207ЯЯ
					'registrationPlace' => [
						'id'   => ArrayHelper::getValue($search, 'city.id'),	//	1
						'zone' => ArrayHelper::getValue($search, 'city.zone'),	//	1
					],
					'year'              => ArrayHelper::getValue($info, 'transport.year'),	//	2008
					'registrationType'	=> 'PERMANENT_WITHOUT_OTK',
					'otkDate' 			=> '',
				],
				'state'           => 'DRAFT',
				'notes' => ArrayHelper::getValue($info, 'contract.comment'),
				'number' => "АВ ".substr(time(), -6, 6),
				'stickerNumber' => "АВ " . substr(time(), -6, 6),
				"customFields"  => [
					[
						"code"  => "number_OSAGO",
						"value" => $osago_number,
					],
					[
						"code"  => "date_OSAGO",
						"value" => date('Y-m-d'),
					],
					[
						"code"  => "TVP",
						"value" => "5",
					],
					[
						"code"  => "issue_ year",
						"value" => ArrayHelper::getValue($info, 'transport.year'),
					],
					[
						"code"  => "category",
						"value" => "k1",
					],
					[
						"code"  => "kasko_number of persons",
						"value" => "2",
					],
					[
						"code"  => "kasko_ feature use",
						"value" => "1",
					],
					[
						"code"  => "kasko_Reason for payment",
						"value" => "3",
					],
					[
						"code"  => "kasko_conditions of insurance compensation",
						"value" => "1",
					],
				],
				"multiObject"   => FALSE,
			];

			//	Преобразование дат
			$contract['send']['dateTo'] = date('Y-m-d', strtotime($contract['send']['dateFrom'].' +1 year -1 day'));
			$contract['send']['dateFrom'] = gmdate('Y-m-d\TH:i:s.000+0000', strtotime($contract['send']['dateFrom']));

			$contract['answer'] = request::execute([
				'action' => 'contract/save',
				'body' => $contract['send'],
			]);
		}

		return $contract;
	}

	/*
	 * Сохранение договора Зелёная Карта
	 */
	public static function greencard($options)
	{
		$contract = [];

		return request::execute([
			'action' => 'contract/save',
			'post' => $contract,
			'headers' => ['Content-Type: application/json; charset=UTF-8']
		]);
	}
}