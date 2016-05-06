<?php
/**
 * Created by PhpStorm.
 * User: letunovskiymn
 * Date: 06.05.16
 * Time: 18:04
 */

namespace AppBundle\Command;

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
//            ->addOption(
//                'yell',
//                null,
//                InputOption::VALUE_NONE,
//                'If set, the task will yell in uppercase letters'
//            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if (!$this->getContainer()->has('app.helper_exchange_rate.'.$name))
        {
            throw new \Exception('Provider not found');
        }

        $provider=$this->getContainer()->get('app.helper_exchange_rate.'.$name);
        $values=$provider->getRateValues();





    }
}