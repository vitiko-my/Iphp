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


class IndexListCommand extends ContainerAwareCommand
{
    /**
     * @var \FOQ\ElasticaBundle\IndexManager
     */
    protected $indexManager;

    protected function configure()
    {
        $this
            ->setName('iphp:elastica:indexlist');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->indexManager = $this->getContainer()->get('foq_elastica.index_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(implode (' ',array_keys ($this->indexManager->getAllIndexes())));
    }

}
