<?php

namespace Iphp\FileStoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Doctrine\Common\EventSubscriber;
use Iphp\FileStoreBundle\FileStorage\FileStorageInterface;
use Iphp\FileStoreBundle\DataStorage\DataStorageInterface;

use Iphp\FileStoreBundle\Driver\AnnotationDriver;
use Iphp\FileStoreBundle\Mapping\PropertyMappingFactory;

use Symfony\Component\HttpFoundation\File\File;

/**
 * UploaderListener.
 *
 * @author Vitiko <vitiko@mail.ru>
 */
class UploaderListener implements EventSubscriber
{
    /**
     * Adapter for Orm or MongoDb
     * @var \Iphp\FileStoreBundle\DataStorage\DataStorageInterface $dataStorage
     */
    protected $dataStorage;


    /**
     * @var \Iphp\FileStoreBundle\FileStorage\FileStorageInterface $fileStorage
     */
    protected $fileStorage;


    /**
     * @var \Iphp\FileStoreBundle\Mapping\PropertyMappingFactory $mappingFactory
     */
    protected $mappingFactory;

    /**
     * Constructs a new instance of UploaderListener.
     *
     * @param \Iphp\FileStoreBundle\DataStorage\DataStorageInterface       $dataStorage  The dataStorage
     * @param \Iphp\FileStoreBundle\FileStorage\FileStorageInterface       $fileStorage  The storage.
     * @param \Iphp\FileStoreBundle\Mapping\PropertyMappingFactory $mappingFactory Mapping Factore
     */
    public function __construct(DataStorageInterface $dataStorage,
                                FileStorageInterface $fileStorage,
                                PropertyMappingFactory $mappingFactory)
    {
        $this->dataStorage = $dataStorage;
        $this->fileStorage = $fileStorage;
        $this->mappingFactory = $mappingFactory;
    }

    /**
     * The events the listener is subscribed to.
     *
     * @return array The array of events.
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postRemove',
        );
    }


    /**
     * Checks for for file to upload.
     *
     * @param \Doctrine\Common\EventArgs $args The event arguments.
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $mappings = $this->mappingFactory->fromEventArgs ($args);

        foreach ($mappings as $mapping) {

            $file = $mapping->getPropertyValue();

            if ($file instanceof File) {
                $fileData = $this->fileStorage->upload($mapping, $file);
                $mapping->setPropertyValue($fileData);
            }
            else $mapping->setPropertyValue(null);
        }
    }

    /**
     * Update the mapped file for Entity (obj)
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs  $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $mappings = $this->mappingFactory->fromEventArgs ($args);

        foreach ($mappings as $mapping) {

            //Uploaded or setted file
            $file = $mapping->getPropertyValue();
            $currentFileData = $args->getOldValue($mapping->getPropertyName());

            //If no new file
            if (is_null($file) || !($file instanceof File)) {
                //Preserve old fileData if current file exists, else null
                $mapping->setPropertyValue(
                    $this->fileStorage->checkFileExists($currentFileData) ? $currentFileData : null
                );
            }
            else if ($file instanceof \Iphp\FileStoreBundle\File\File && $file->isDeleted()) {
                if ($this->fileStorage->removeFile($currentFileData)) $mapping->setPropertyValue(null);
            }
            else {
                if ($currentFileData && !$this->fileStorage->isSameFile ($file,$currentFileData))
                        $this->fileStorage->removeFile($currentFileData);

                $fileData = $this->fileStorage->upload($mapping, $file);
                $mapping->setPropertyValue($fileData);
            }
        }
        $this->dataStorage->recomputeChangeSet($args);
    }




    /**
     * Removes the file if necessary.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args The event arguments.
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $mappings = $this->mappingFactory->fromEventArgs ($args);

        foreach ($mappings as $mapping) {
            $this->fileStorage->removeByMapping($mapping);
        }

    }

}
