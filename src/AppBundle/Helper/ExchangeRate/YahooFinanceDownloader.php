<?php

/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 15:42
 */
namespace AppBundle\Helper\ExchangeRate;

use GuzzleHttp\Client;

class YahooFinanceDownloader extends BaseExchange implements ExchangeRateProvider
{

    /**
     * @var array|string $currencies Массив из трехзначных кодов или строка с перечислением
     * @return array
     *
     * Метод должен возвращать массив следующего вида
     * [ 'date' => '2016­03­02 10:23:59', 'rates' => [ 'USD' => 73.0865, 'EUR' => 80.1231 ]]
     *
     * Курсы валют в ноде rates должны быть для тех валют,
     * которые переданы в параметре $currencies через запятую или массивом
     */
    public function getRateValues($currencies = 'USD, EUR')
    {
        $currenciesData = $this->validateInputData($currencies);

        $resultData = array('date' => $this->date->format('Y-m-d H:i:s'), 'rates' => array());
        $url = $this->container->getParameter('url_yahoo');
        $baseCurrency = $this->container->getParameter('base_currency_yahoo');
        $resultAry = array();
        foreach ($currenciesData as $currency) {
            $currency .= $baseCurrency;
            $resultAry[] = $currency;
        }
        $url = str_replace('USDRUB', implode(',', $resultAry), $url);
        $resultData['rates'] = $this->getDataFromResource($url);
        return $resultData;
    }

    /**
     * Получает данные и возвращает массив тех данных что требуются
     * @param string $url
     * @return array
     * @throws \Exception
     */
    protected function getDataFromResource($url)
    {
        $result = array();
        $client = new Client();
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() != 200 && $response->getHeader('Content-Type') == 'text/json') {
            throw new \Exception('Server not available or data not valid');
        }
        $resultResponse = json_decode($response->getBody()->getContents(), true);
        if ($error = json_last_error()) {
            throw new \Exception('JSON parse error ' . $error);
        }
        //TODO лучше преобраззовать в объект, будет быстрее и проще
        if (!empty($resultResponse['query']['results'])) {
            $oneElement = $resultResponse['query']['results']['rate'];
            if (!empty($oneElement['id'])) {
                $currencyMod = $this->modifyData($oneElement['id'], $oneElement['Rate']);
                $result[key($currencyMod)] = current($currencyMod);
            } else {
                foreach ($oneElement as $rate) {
                    $currencyMod = $this->modifyData($rate['id'], $rate['Rate']);
                    $result[key($currencyMod)] = current($currencyMod);
                }
            }
        }
        return $result;
    }

    /**
     * Модификация перед отправкой в метод getRateValues
     *
     * @param $name
     * @param $currency
     * @return array
     */
    private function modifyData($name, $currency)
    {
        return [substr($name, 0, 3) => floatval($currency)];
    }

}