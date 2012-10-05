<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;


class TypeEntriesCountCommand extends ContainerAwareCommand
{
    /**
     * @var \FOQ\ElasticaBundle\Provider\ProviderRegistry
     */
    private $providerRegistry;


    protected function configure()
    {
        $this
            ->setName('iphp:elastica:typeentriescount')
            ->addOption('index', null, InputOption::VALUE_REQUIRED, 'The index to count')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type to count');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->providerRegistry = $this->getContainer()->get('foq_elastica.provider_registry');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $index  = $input->getOption('index');
        $type   = $input->getOption('type');

        if (!$index) $index = 'iphp';


        $provider = $this->providerRegistry->getProvider($index,  $type);

        $output->writeln( method_exists($provider,'entriesNum')? $provider->entriesNum() : 0);

    }

}
