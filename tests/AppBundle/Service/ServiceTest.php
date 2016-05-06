<?php

/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 20:57
 */
namespace Tests\AppBundle\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;
//    /**
//     * @var EntityManagerInterface $em
//     */
//    protected $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
//        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * Тестирует регион
     * @dataProvider getServiceName
     * @param $name
     * @param $currency
     * @param $resultResponse
     */
    public function testService($name,$currency,$resultResponse)
    {
        $provider=$this->container->get('app.helper_exchange_rate.'.$name);
        try
        {
            $result=$provider->getRateValues($currency);
            $this->assertTrue(is_array($result));
            $this->assertNotEmpty($result['date']);
            $this->assertNotEmpty($result['rates']);
            $this->assertNotEquals(current($result['rates']),'N/A');
        }
        catch(\Exception $e)
        {
            $this->assertFalse($resultResponse);
        }
    }

    public function getServiceName()
    {
        return array(
            ['cbr','usd,eur',true],
            ['cbr','USD,eur',true],
            ['cbr','USDs,eur',false],
            ['cbr','USD',true],
            ['cbr','usd',true],
            ['cbr','USDA',false],
            ['cbr','usda',false],
            ['cbr','usD',true],
            ['cbr',array('usd','eur'),true],
            ['cbr',array('USD','eur'),true],
            ['cbr',array('USD','EUR'),true],
            ['cbr',array('USD'),true],
            ['cbr',array('usd'),true],
            ['cbr',array('USDA'),false],
            ['cbr',array('usda'),false],
            ['cbr',array('usds','eur'),false],

            ['yahoo_finance','usd,eur',true],
            ['yahoo_finance','USD,eur',true],
            ['yahoo_finance','USDs,eur',false],
            ['yahoo_finance','USD',true],
            ['yahoo_finance','usd',true],
            ['yahoo_finance','USDA',false],
            ['yahoo_finance','usda',false],
            ['yahoo_finance','usD',true],
            ['yahoo_finance',array('usd','eur'),true],
            ['yahoo_finance',array('USD','eur'),true],
            ['yahoo_finance',array('USD','EUR'),true],
            ['yahoo_finance',array('USD'),true],
            ['yahoo_finance',array('usd'),true],
            ['yahoo_finance',array('USDA'),false],
            ['yahoo_finance',array('usda'),false],
            ['yahoo_finance',array('usds','eur'),false],
        );
    }
}
