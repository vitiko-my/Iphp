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


class TypeListCommand extends ContainerAwareCommand
{
    /**
     * @var \FOQ\ElasticaBundle\Provider\ProviderRegistry
     */
    private $providerRegistry;


    protected function configure()
    {
        $this
            ->setName('iphp:elastica:typelist');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->providerRegistry = $this->getContainer()->get('foq_elastica.provider_registry');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(implode (' ',array_keys ($this->providerRegistry->getIndexProviders('iphp'))));

    }

}
