<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */


namespace Iphp\SearchBundle\Command;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

use FOQ\ElasticaBundle\Command\PopulateCommand as FOQPopulateCommand;
/**
 * Populate the search index
 */
class PopulateCommand extends  FOQPopulateCommand
{

    /**
     * @var \FOQ\ElasticaBundle\IndexManager
     */
    protected $indexManager;

    /**
     * @var \FOQ\ElasticaBundle\Provider\ProviderRegistry
     */
    protected $providerRegistry;

    /**
     * @var \FOQ\ElasticaBundle\Resetter
     */
    protected $resetter;

    protected function configure()
    {
        $this
            ->setName('iphp:elastica:populate')
            ->addOption('index', null, InputOption::VALUE_REQUIRED, 'The index to repopulate')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type to repopulate')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Max number of entries')
            ->addOption('start', null, InputOption::VALUE_REQUIRED, 'Start from pos')
            ->addOption('no-reset', null, InputOption::VALUE_NONE, 'If set, the indexes will not been resetted before populating.')
            ->setDescription('Populates search indexes from providers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $index  = $input->getOption('index');
        $type   = $input->getOption('type');
        $limit   = $input->getOption('limit');
        $start   = $input->getOption('start');
        $reset  = $input->getOption('no-reset') ? false : true;



        print $index.'.'.$type;

        $this->populateIndexTypeLimit($output, $index, $type, $reset, $limit, $start);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->indexManager = $this->getContainer()->get('foq_elastica.index_manager');
        $this->providerRegistry = $this->getContainer()->get('foq_elastica.provider_registry');
        $this->resetter = $this->getContainer()->get('foq_elastica.resetter');
    }


    /**
     * Deletes/remaps an index type, populates it, and refreshes the index.
     *
     * @param OutputInterface $output
     * @param string          $index
     * @param string          $type
     * @param boolean         $reset
     */
    private function populateIndexTypeLimit(OutputInterface $output, $index, $type, $reset, $limit, $start)
    {
        if ($reset) {
            $output->writeln(sprintf('Resetting: %s/%s', $index, $type));
            $this->resetter->resetIndexType($index, $type);
        }

        $loggerClosure = function($message) use ($output, $index, $type) {
            $output->writeln(sprintf('Populating: %s/%s, %s', $index, $type, $message));
        };

        $provider = $this->providerRegistry->getProvider($index, $type);
        $provider->populate($loggerClosure, array ('limit' => $limit, 'start' => $start));

        $output->writeln(sprintf('Refreshing: %s', $index));
        $this->indexManager->getIndex($index)->refresh();
    }
}