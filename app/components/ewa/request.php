<?php

namespace app\components\ewa;

class request
{
	/*
	 * Метод выполняет запрос к сервису.
	 * Если сервер сообщает что авторизация не выполнена - выполняет авторизацию и повторяет запрос
	 */
	public static function execute($options)
	{
		$result = self::send($options);

		//	Если возвращён ответ "Не авторизовано", делаю запрос авторизации и повторяю текущий запрос
		if (isset($result['answer_headers'][0]) && strstr(strtolower($result['answer_headers'][0]), 'unauthorized'))
		{
			find::auth(TRUE);

			$result = self::send($options);
		}

		return $result['answer_data'];
	}

	/*
	 * Метод выполняет непосредственный запрос к сервису с указанными параметрами
	 */
	public static function send($options)
	{
		$result = [
			'success' => FALSE,
			'time' => microtime(TRUE),
			'url' => '',
			'cookie_file' => '',
			'send_data' => [],
			'send_headers' => [],
			'answer_headers' => [],
			'answer_data' => []
		];
		$options = array_merge(\Yii::$app->params['ewa'], $options);

		if (isset($options['host']) && isset($options['action']) && isset($options['cookie_file']) && strlen($options['host']) && strlen($options['action']) && strlen($options['cookie_file']))
		{
			$curl = curl_init();

			if (is_resource($curl))
			{
				$result['cookie_file'] = $options['cookie_file'];
				$result['url'] = $options['host'].$options['action'];
				if (isset($options['get']) && is_array($options['get']))
				{
					$result['url'] .= '?'.http_build_query($options['get']);
				}

				$curl_conf = [
					CURLOPT_URL 			=> $result['url'],				//	Адрес запроса
					CURLOPT_HTTP_VERSION	=> CURL_HTTP_VERSION_1_0,		//	Использовать версию протокола HTTP/1.0
					CURLOPT_RETURNTRANSFER	=> true,						//	Вернуть ответ в виде строки, а не выводить в браузер
					CURLOPT_HEADER			=> true,						//	Добавить заголовки в ответ
					CURLOPT_CONNECTTIMEOUT	=> 30,							//	Маскимальное время ожидания соединения (сек)
					CURLOPT_TIMEOUT			=> 30,							//	Максимальное время выполнения (сек)
					CURLOPT_FOLLOWLOCATION	=> true,						//	Переходить по редиректам (Location: ...)
					CURLOPT_FRESH_CONNECT	=> false,						//	Принудительное использование нового соединения вместо закэшированного
					CURLINFO_HEADER_OUT		=> false,						//	Для отслеживания строки запроса дескриптора
					CURLOPT_AUTOREFERER		=> true,						//	Автоматически добавлять HTTP_REFERER при переходе по редиректам
					CURLOPT_MAXREDIRS		=> 10,							//	Максимальное количество обрабатываемых редиректов
					CURLOPT_SSL_VERIFYPEER	=> 0,							//	Не проверять SSL сертификат
					CURLOPT_SSL_VERIFYHOST	=> 0,							//	Не проверять Host SSL сертификата
					CURLOPT_COOKIEFILE		=> $options['cookie_file'],		//	Файл с cookie-данными, которые будут отправлены в запросе
					CURLOPT_COOKIEJAR		=> $options['cookie_file'],		//	Файл, в который будут записаны cookie-данные из ответа
					CURLOPT_HTTPHEADER		=> []							//	Дополнительные заголовки запроса
				];

				//	Дополнительные заголовки
				if (isset($options['headers']) && is_array($options['headers']) && count($options['headers']))
				{
					$curl_conf[CURLOPT_HTTPHEADER] = array_merge($curl_conf[CURLOPT_HTTPHEADER], $options['headers']);
				}

				//	POST данные
				if (isset($options['post']) && $options['post'])
				{
					$curl_conf[CURLOPT_POST] = true;			//	Тип запроса - POST
					if(is_array($options['post']))
					{
						$options['post'] = http_build_query($options['post']);
					}
					$result['send_data'] = $options['post'];
					$curl_conf[CURLOPT_POSTFIELDS] = $options['post'];	//	Данные POST запроса
				}
				elseif (isset($options['body']) && $options['body'])
				{
					if (is_array($options['body']))
					{
						$options['body'] = json_encode($options['body'], JSON_UNESCAPED_UNICODE);
					}
					$result['send_data'] = $options['body'];
					$curl_conf[CURLOPT_POSTFIELDS] = $options['body'];
					$curl_conf[CURLOPT_HTTPHEADER] = array_merge($curl_conf[CURLOPT_HTTPHEADER], ['Content-Type: application/json; charset=UTF-8']);
				}
				else
				{
					$curl_conf[CURLOPT_POST] = false;
				}

				$result['send_headers'] = $curl_conf[CURLOPT_HTTPHEADER];

				curl_setopt_array($curl, $curl_conf);

				$answer = curl_exec($curl);

				if (strlen($answer))
				{
					$answer = explode("\r\n\r\n", $answer, 2);
					if (!isset($answer[1]))
					{
						$answer[1] = '';
					}

					$h = explode("\n", $answer[0]);
					foreach ($h as $one)
					{
						$one = explode(':', $one, 2);
						if (count($one) == 2)
						{
							$result['answer_headers'][$one[0]] = trim($one[1]);
						}
						else
						{
							$result['answer_headers'][] = trim($one[0]);
						}
					}

					$result['success'] = TRUE;
					$result['answer_data'] = $answer[1];
					if (!isset($options['raw']) || !$options['raw'])
					{
						$result['answer_data'] = (array)json_decode($result['answer_data'], TRUE);
					}

					curl_close($curl);
				}
			}
		}

		$result['time'] = microtime(TRUE) - $result['time'];

		return $result;
	}
}