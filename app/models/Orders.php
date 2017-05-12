<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Orders extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'landing_orders';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [];
    }

    public static function getCurrent($type = 'osago')
    {
        $order = self::findOne(Yii::$app->session->get($type.'_order_id'));
        if (!$order)
        {
            //	Создаю новый пустой заказ, в который будут добавляться данные на всех шагах оформления заказа
            $order = new self();
            $order->domain = Yii::$app->request->serverName;
            $order->type = $type;
            $order->insert(false);
            Yii::$app->session->set($type.'_order_id', $order->id);
        }

        return $order;
    }

    public function getSearchData()
    {
        if (strlen($this->search))
        {
            return json_decode($this->search, true);
        }
        return [];
    }

    public function getOfferData()
    {
        if (strlen($this->offer))
        {
            return json_decode($this->offer, TRUE);
        }
        return [];
    }

    public function getAuthData()
    {
        if (strlen($this->auth))
        {
            return json_decode($this->auth, TRUE);
        }
        return [];
    }

    public function getInfoData()
    {
        if (strlen($this->info))
        {
            return json_decode($this->info, TRUE);
        }
        return [];
    }

    public function getDeliveryData()
    {
        if (strlen($this->delivery))
        {
            return json_decode($this->delivery, TRUE);
        }
        return [];
    }

    public function getContractData()
    {
        if (strlen($this->contract))
        {
            return json_decode($this->contract, TRUE);
        }
        return [];
    }

    public function getResultData()
    {
        if (strlen($this->result))
        {
            return json_decode($this->result, TRUE);
        }
        return [];
    }

    public function getPaymentData()
    {
        if (strlen($this->payment))
        {
            return json_decode($this->payment, TRUE);
        }
        return [];
    }

    public function getTaskData()
    {
        $search = $this->getSearchData();
        $offer = $this->getOfferData();
        $auth = $this->getAuthData();
        $info = $this->getInfoData();
        $delivery = $this->getDeliveryData();

        $info = [
            'Номер заказа на сайте' => $this->id,
            'Способ оформления' => ArrayHelper::getValue($auth, 'type') == 'phone' ? 'По телефону' : 'На сайте',
            'Параметры поиска' => [
                'Тип ТС' => ArrayHelper::getValue($search, 'car_category.code').' ('.ArrayHelper::getValue($search, 'car_category.name').')',
                'Город регистрации' => ArrayHelper::getValue($search, 'city.name'),
                'Используется как такси' => ArrayHelper::getValue($search, 'not_taxi') ? 'Нет' : 'Да',
                'Год выпуска ТС' => ArrayHelper::getValue($search, 'year'),
            ],
            'Пакет страхования' => [
                'Название' => ArrayHelper::getValue($offer, 'type'),
                'Стоимость' => (float)ArrayHelper::getValue($offer, 'osago.payment') + (float)ArrayHelper::getValue($offer, 'dgo.payment') + (float)ArrayHelper::getValue($offer, 'auto.payment'),
            ],
            'Клиент' => [
                'Фамилия' => ArrayHelper::getValue($info, 'customer.name_last'),
                'Имя' => ArrayHelper::getValue($info, 'customer.name_first'),
                'Отчество' => ArrayHelper::getValue($info, 'customer.name_middle'),
                'Дата рождения' => ArrayHelper::getValue($info, 'customer.birth_date'),
                'ИНН' => ArrayHelper::getValue($info, 'customer.code'),
                'Телефон' => ArrayHelper::getValue($info, 'customer.phone'),
                'E-mail' => ArrayHelper::getValue($info, 'customer.email'),
                'Адрес проживания' => ArrayHelper::getValue($info, 'customer.address'),
            ],
            'Документ' => [
                'Тип' => ArrayHelper::getValue($info, 'document.type'),
                'Серия' => ArrayHelper::getValue($info, 'document.series'),
                'Номер' => ArrayHelper::getValue($info, 'document.number'),
                'Кем выдан' => ArrayHelper::getValue($info, 'document.issued_by'),
                'Когда выдан' => ArrayHelper::getValue($info, 'document.issued_date'),
            ],
            'ТС' => [
                'Марка' => ArrayHelper::getValue($info, 'transport.vendor'),
                'Модель' => ArrayHelper::getValue($info, 'transport.model'),
                'Год выпуска' => ArrayHelper::getValue($info, 'transport.year'),
                'Номер кузова VIN' => ArrayHelper::getValue($info, 'transport.vin_number'),
                'Номерной знак' => ArrayHelper::getValue($info, 'transport.gov_number'),
            ],
            'Договор' => [
                'Дата начала действия' => ArrayHelper::getValue($info, 'contract.start_date'),
                'Комментаий к заказу' => ArrayHelper::getValue($info, 'contract.comment'),
            ],
            'Доставка и оплата' => [
                'Способ доставки' => ArrayHelper::getValue($delivery, 'delivery') == 'courier' ? 'Курьером' : 'Новой почтой',
                'Адрес доставки' => ArrayHelper::getValue($delivery, 'address'),
                'Город' => ArrayHelper::getValue($delivery, 'city'),
                'Отделение НП' => ArrayHelper::getValue($delivery, 'filial'),
                'Способ оплаты' => ArrayHelper::getValue($delivery, 'pay') == 'online' ? 'Online Visa / Mastercard' : 'Наличными в момент получения',
            ]
        ];

        if (!$info['Клиент']['Имя'])
        {
            $info['Клиент']['Имя'] = $this->name;
        }
        if (!$info['Клиент']['Телефон'])
        {
            $info['Клиент']['Телефон'] = $this->phone;
        }

        foreach($info as $key => $value)
        {
            if (is_array($value))
            {
                foreach ($value as $k => $v)
                {
                    $value[$k] = '<strong>'.$k.':</strong> '.$v;
                }

                $info[$key] = '<strong>'.$key.':</strong><br>'
                    .'<div style="padding-left:25px">'.implode('<br>', $value).'</div>';
            }
            else
            {
                $info[$key] = '<strong>'.$key.':</strong> '.$value;
            }
        }
        $info = implode('<br>', $info);

        return $info;
    }
}