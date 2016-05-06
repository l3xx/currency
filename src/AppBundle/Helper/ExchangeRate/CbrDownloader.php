<?php

/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 15:41
 */
namespace AppBundle\Helper\ExchangeRate;
use DateTime;
use DOMElement;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CbrDownloader extends BaseExchange implements ExchangeRateProvider
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

        $currenciesData=$this->validateInputData($currencies);

        $resultData=array('date'=>$this->date->format('Y-m-d H:i:s'),'rates'=>array());
        $url=$this->container->getParameter('url_cbr');
        $result=$this->getDataFromResource($url,$this->date);
        foreach ($currenciesData as $currency)
        {
            // TODO либо сделать проверку и что нибудь писать в случает отсутсвия такой валюты на сервере
            $resultData['rates'][$currency]='N/A';
            if (!empty($result[$currency]))
            {
                $resultData['rates'][$currency]=$result[$currency];
            }
        }
        return $resultData;
    }

    /**
     * Получает данные и возвращает массив тех данных что требуются
     * @param string $url
     * @param DateTime $date
     * @return array
     * @throws \Exception
     */
    protected function getDataFromResource($url, DateTime $date)
    {
        $result=array();
        $client = new Client();
        $response = $client->request('GET', $url, [
            'form_params' => [
                'date_req'=>$date->format('d/m/Y')
                ]
            ]);
        if ($response->getStatusCode()!=200  && $response->getHeader('Content-Type')=='text/xml')
        {
            throw new \Exception('Server not available or data not valid');
        }
        $crawler = new Crawler($response->getBody()->getContents());
        $nodeValute=$crawler->filter('ValCurs>Valute');
        /** @var DOMElement $valute */
        foreach ($nodeValute as $valute)
        {
            $currencyName=$valute->childNodes[3]->nodeValue;// название валюты
            $currencyValue=floatval(str_replace(",",".",$valute->childNodes[9]->nodeValue));// значение валюты
            $result[$currencyName]=$currencyValue;
        }

        return $result;
    }

}