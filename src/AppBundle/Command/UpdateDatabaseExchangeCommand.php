<?php
/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 18:04
 */

namespace AppBundle\Command;

use AppBundle\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDatabaseExchangeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('exchange:update')
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name service for update'
            )
            ->addArgument(
                'currencies',
                InputArgument::OPTIONAL,
                'Name service for update',
                'USD,EUR'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $currencies = $input->getArgument('currencies');
        if (!$this->getContainer()->has('app.helper_exchange_rate.' . $name)) {
            throw new \Exception('Provider not found');
        }
        $provider = $this->getContainer()->get('app.helper_exchange_rate.' . $name);
        $values = $provider->getRateValues($currencies);
        $manager = $this->getContainer()->get('doctrine')->getManager();
        if (!empty($values['rates'])) {
            foreach ($values['rates'] as $nameCurrency => $currencyResponse) {
                $currency = new Currency();
                $currency->setProvider($name);
                $currency->setName($nameCurrency);
                $currency->setValue($currencyResponse);
                $currency->setDate(new \DateTime($values['date']));
                $output->writeln("<info>Currency: " . $nameCurrency . ", value: " . $currencyResponse . " added<info>");
                $manager->persist($currency);
            }
            $manager->flush();
        }
        $output->writeln("<info>Completed<info>");
    }
}