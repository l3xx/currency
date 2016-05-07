<?php
namespace AppBundle\Helper\ExchangeRate;

use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 16:15
 */
class BaseExchange
{
    protected $container;
    protected $date;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->date = new DateTime();
    }

    /**
     * Проверяет входящую строку и бросает исключения
     *
     * @param array|string $currencies
     * @return array
     * @throws Exception
     */
    protected function validateInputData($currencies)
    {
        if (empty($currencies)) {
            throw new Exception('Currencies is empty');
        }

        $dataCurrencies = $currencies;
        if (!is_array($currencies)) {
            $dataCurrencies = explode(',', $currencies);
        }

        if (empty($dataCurrencies)) {
            throw new Exception('Currencies is not valid');
        }

        $dataCurrenciesModify = array();
        foreach ($dataCurrencies as $currency) {
            $currency = mb_strtoupper(trim($currency));
            if ($this->validateAmountCurrency($currency)) {
                $dataCurrenciesModify[] = $currency;
            } else {
                throw new Exception(printf('Currency "%s" is not valid', $currency));
            }
        }
        return $dataCurrenciesModify;
    }


    /**
     * Делает проверку по названию валюты
     *
     * @param string $currency
     * @return bool
     */
    protected function validateAmountCurrency($currency)
    {
        $res = false;
        $result = preg_match('/^[A-Z]{3}/', $currency, $matches);
        if ($result) {
            $res = true;
        }
        return $res;
    }


}