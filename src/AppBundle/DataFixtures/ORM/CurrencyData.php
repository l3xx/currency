<?php
/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 27.04.16
 * Time: 2:19
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Book;
use AppBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadBookData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setName('USD');
        $currency->setValue(70.8768);
        $currency->setProvider('cbr');
        $date= new \DateTime();
        $currency->setProvider($date);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('EUR');
        $currency->setValue(75.8768);
        $currency->setProvider('cbr');
        $date= new \DateTime();
        $currency->setProvider($date);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('USD');
        $currency->setValue(70.8768);
        $currency->setProvider('cbr');
        $date= new \DateTime();
        $currency->setProvider($date->modify('-1 day'));
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('EUR');
        $currency->setValue(75.8768);
        $currency->setProvider('cbr');
        $date= new \DateTime();
        $currency->setProvider($date->modify('-1 day'));
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('USD');
        $currency->setValue(70.8768);
        $currency->setProvider('yahoo_finance');
        $date= new \DateTime();
        $currency->setProvider($date);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('EUR');
        $currency->setValue(75.8768);
        $currency->setProvider('yahoo_finance');
        $date= new \DateTime();
        $currency->setProvider($date);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('USD');
        $currency->setValue(70.8768);
        $currency->setProvider('yahoo_finance');
        $date= new \DateTime();
        $currency->setProvider($date->modify('-1 day'));
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setName('EUR');
        $currency->setValue(75.8768);
        $currency->setProvider('yahoo_finance');
        $date= new \DateTime();
        $currency->setProvider($date->modify('-1 day'));
        $manager->persist($currency);

        $manager->flush();
    }
}