<?php
/**
 * Created by PhpStorm
 * User: Rushan Zaripov
 * Date: 24.03.2021
 * Time: 14:57
 */

namespace Ozna\Tools\Url;


class Scanner
{
	/**
	 * @var array - Массив URL адресов
	 */
	protected $urls;

	/**
	 * @var \GuzzleHttp\Client - объект, который делает запросы на удаленный сервер
	 */
	protected $httpClient;

	/**
	 * Scanner constructor
	 * @param array $urls - массив адресов для сканирования
	 */
	public function __construct(array $urls)
	{
		$this->urls    = $urls;
		$this->httpClient = new \GuzzleHttp\Client();
	}

	/**
	 * Получаем список невалидных неотвечающих адресов
	 * @return array - массив невалидных адресов
	 */
	public function getInvalidUrls()
	{
		$invalidUrls = [];

		foreach ( $this->urls as $url)
		{
			try {
				$statusCode = $this->getStatusCodeForUrl( $url );
			} catch ( \Exception $e ) {
				$statusCode = 500;
			}

			if ( $statusCode >= 400) {
				array_push( $invalidUrls, array(
					'url'     => $url,
					'status'  => $statusCode
				) );
			}
		}

		return $invalidUrls;
	}

	/**
	 * Возвращает код состояния HTTP для URL-адреса
	 * @param $url - удаленный URL адрес
	 * @return mixed - Код состояния HTTP
	 */
	protected function getStatusCodeForUrl( $url ) {
		$httpResponce = $this->httpClientOptions( $url );

		return $httpResponce->getStatusCode();
	}

}