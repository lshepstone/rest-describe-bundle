<?php

namespace Sheppers\RestDescribeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DescribeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rest:describe')
            ->setDescription('Generates a relational schema from @Describe annotations')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('sheppers_rest_describe.describer')->describe();
    }
}
