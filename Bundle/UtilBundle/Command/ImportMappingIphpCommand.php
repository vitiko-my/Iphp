<?php
/**
 * Created by Vitiko
 * Date: 03.08.11
 * Time: 18:44
 */

namespace Iphp\Bundle\UtilBundle\Command;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\Export\ClassMetadataExporter;
use Doctrine\ORM\Tools\Console\MetadataFilter;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;

/**
 * Import Doctrine ORM metadata mapping information from an existing database.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class ImportMappingIphpCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('iphp:mapping:import')
            ->addArgument('bundle', InputArgument::REQUIRED, 'The bundle to import the mapping information to')
          //  ->addArgument('mapping-type', InputArgument::OPTIONAL, 'The mapping type to export the imported mapping information to')
            ->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match entities that should be mapped.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force to overwrite existing mapping files.')
            ->setDescription('Import mapping information from an existing database')
            ->setHelp(<<<EOT
The <info>doctrine:mapping:import</info> command imports mapping information
from an existing database:

<info>php app/console doctrine:mapping:import "MyCustomBundle" xml</info>

You can also optionally specify which entity manager to import from with the
<info>--em</info> option:

<info>php app/console doctrine:mapping:import "MyCustomBundle" xml --em=default</info>

If you don't want to map every entity that can be found in the database, use the
<info>--filter</info> option. It will try to match the targeted mapped entity with the
provided pattern string.

<info>php app/console doctrine:mapping:import "MyCustomBundle" xml --filter=MyMatchedEntity</info>

Use the <info>--force</info> option, if you want to override existing mapping files:

<info>php app/console doctrine:mapping:import "MyCustomBundle" xml --force</info>
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $this->getApplication()->getKernel()->getBundle($input->getArgument('bundle'));

        $destPath = $bundle->getPath();

        
      /*  $type = $input->getArgument('mapping-type') ? $input->getArgument('mapping-type') : 'xml';
        if ('annotation' === $type) {
            $destPath .= '/Entity/Base';
        } else {
            $destPath .= '/Resources/config/doctrine';
        }
        if ('yaml' === $type) {
            $type = 'yml';
        }*/

        $cme = new ClassMetadataExporter();
        $exporter = $cme->getExporter($type);
        $exporter->setOverwriteExistingFiles($input->getOption('force'));

        if ('annotation' === $type) {
            $entityGenerator = $this->getEntityGenerator();
            $exporter->setEntityGenerator($entityGenerator);
        }

        $em = $this->getEntityManager($input->getOption('em'));

        $databaseDriver = new DatabaseDriver($em->getConnection()->getSchemaManager());
        $em->getConfiguration()->setMetadataDriverImpl($databaseDriver);

        $emName = $input->getOption('em');
        $emName = $emName ? $emName : 'default';

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
        $metadata = $cmf->getAllMetadata();
        $metadata = MetadataFilter::filter($metadata, $input->getOption('filter'));
        if ($metadata) {
            $output->writeln(sprintf('Importing mapping information from "<info>%s</info>" entity manager', $emName));
            foreach ($metadata as $class) {
                $className = $class->name;
                $class->name = $bundle->getNamespace().'\\Entity\\Base\\'.$className;
                if ('annotation' === $type) {
                    $path = $destPath.'/'.$className.'.php';
                } else {
                    $path = $destPath.'/'.$className.'.orm.'.$type;
                }
                $output->writeln(sprintf('  > writing <comment>%s</comment>', $path));
                $code = $exporter->exportClassMetadata($class);
                if (!is_dir($dir = dirname($path))) {
                    mkdir($dir, 0777, true);
                }


                $code = str_replace ('private $','protected $', $code);


                file_put_contents($path, $code);

                $mainFilePath =  $destPath.'/../'.$className.'.php';


                $output->writeln(sprintf('  > writing <comment>%s</comment>', $mainFilePath));

                file_put_contents($mainFilePath,
                '<?php'."\n\n".
                'namespace '.$bundle->getNamespace().'\\Entity;'."\n\n".
                'use '.$bundle->getNamespace().'\\Entity\\Base\\'.$className.' as Base'.$className.';'."\n\n".
                'class '.$className.' extends Base'.$className."\n".'{'."\n".'}');
            }
        } else {
            $output->writeln('Database does not have any mapping information.', 'ERROR');
            $output->writeln('', 'ERROR');
        }
    }
}
