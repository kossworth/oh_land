<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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

    protected function url_origin( $use_forwarded_host = false )
    {
        $s        = $_SERVER;
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
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

    public function getFilesData()
    {
        if (strlen($this->files))
        {
            $files = explode(';', $this->files);
            array_pop($files);
            return $files;
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
            return $this->payment;
        }
        return [];
    }

    public function getTaskData()
    {
        $search = $this->getSearchData();
        $offer = $this->getOfferData();
        $payment = $this->getPaymentData();
        $info = $this->getInfoData();
        $files = $this->getFilesData();
        $contract = $this->getResultData();
        $delivery = $this->getDeliveryData();

        $result = [
            'Номер заказа на сайте' => $this->id,
//            'Способ оформления' => ArrayHelper::getValue($auth, 'type') == 'phone' ? 'По телефону' : 'На сайте',
            'Параметры поиска' => [
                'Категория ТС' => ArrayHelper::getValue($search, 'autoCategory'),
                'Город регистрации' => ArrayHelper::getValue($search, 'city.name'),
                'Используется как такси' => (ArrayHelper::getValue($search, 'taxi') == false) ? 'Нет' : 'Да',
            ],
            'Пакет страхования' => [
                'Идентификатор' => ArrayHelper::getValue($offer, 'tariff.id'),
                'Название' => ArrayHelper::getValue($offer, 'tariff.name'),
                'Стоимость' => ArrayHelper::getValue($offer, 'discountedPayment'),
            ],
            'Клиент' => [
                'Фамилия' => ArrayHelper::getValue($info, 'customer.name_last'),
                'Имя' => ArrayHelper::getValue($info, 'customer.name_first'),
                'ИНН' => ArrayHelper::getValue($info, 'customer.code'),
                'Телефон' => ArrayHelper::getValue($info, 'customer.phone'),
                'E-mail' => ArrayHelper::getValue($info, 'customer.email'),
                'Адрес проживания' => ArrayHelper::getValue($info, 'customer.address'),
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
            'Оплата' => [
                'Способ оплаты' => $payment == 'card' ? 'Оплата онлайн' : 'Наличными в момент получения',
            ],
            'Доставка' => [
                'Способ доставки' => ArrayHelper::getValue($delivery, 'type'),
                'Город' => ArrayHelper::getValue($delivery, 'city'),
                'Адрес доставки' => ArrayHelper::getValue($delivery, 'address'),
//                'Отделение НП' => ArrayHelper::getValue($delivery, 'filial'),
            ]
        ];
        
        if (!$result['Клиент']['Имя'])
        {
            $result['Клиент']['Имя'] = $this->name;
            unset($result['Клиент']['Фамилия']);
        }
        if (!$result['Клиент']['Телефон'])
        {
            $result['Клиент']['Телефон'] = $this->phone;
        }
        if (!$result['Договор']['Комментаий к заказу'])
        {
            unset($result['Договор']['Комментаий к заказу']);
        }
        if (!count($delivery))
        {
            unset($result['Доставка']);
        }
        if (!$payment)
        {
            unset($result['Оплата']);
        }
        if(!$info)
        {
            unset($result['Договор']);
            unset($result['ТС']);
            unset($result['Клиент']['ИНН']);
            unset($result['Клиент']['E-mail']);
            unset($result['Клиент']['Адрес проживания']);
        }

        if(count($files))
        {
            $result["Загруженные файлы"] = [];
            $counter = 1;
            foreach ($files as $file)
            {
                $result["Загруженные файлы"][$counter] = $this->url_origin().$file;
                $counter++;
            }
        }
        
        if(ArrayHelper::getValue($delivery, 'type') == 'Доставка Новой Почтой')
        {
            unset($result['Доставка']['Адрес доставки']);
            $result['Доставка']['Отделение НП'] = ArrayHelper::getValue($delivery, 'address');
        }
        
        foreach($result as $key => $value)
        {
            if (is_array($value))
            {
                foreach ($value as $k => $v)
                {
                    $value[$k] = '<strong>'.$k.':</strong> '.$v;
                }

                $result[$key] = '<strong>'.$key.':</strong><br>'
                    .'<div style="padding-left:25px">'.implode('<br>', $value).'</div>';
            }
            else
            {
                $info[$key] = '<strong>'.$key.':</strong> '.$value;
            }
        }
        $result = implode('<br>', $result);
        return $result;
    }
}