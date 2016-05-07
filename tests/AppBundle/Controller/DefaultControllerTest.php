<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAjax()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ajax');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/ajax?currencies%5B0%5D%5Bname%5D=usd&currencies%5B0%5D%5Bsource%5D=cbr&currencies%5B1%5D%5Bname%5D=eur&currencies%5B1%5D%5Bsource%5D=cbr&currencies%5B2%5D%5Bname%5D=usd&currencies%5B2%5D%5Bsource%5D=yahoo_finance&currencies%5B3%5D%5Bname%5D=eur&currencies%5B3%5D%5Bsource%5D=yahoo_finance&date=Sat+May+07+2016+14%3A48%3A24+GMT%2B0300+(MSK)');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
