<?php

namespace Iphp\FileStoreBundle\Mapping;

use Iphp\FileStoreBundle\Naming\NamerInterface;
use Iphp\FileStoreBundle\Naming\DirectoryNamerInterface;

/**
 * PropertyMapping.
 *
 * @author Vitiko <vitiko@mail.ru>
 *
 */
class PropertyMapping
{


    protected $obj;
    /**
     * @var \ReflectionProperty $property
     */
    protected $property;

    /**
     * @var \ReflectionProperty $fileNameProperty
     */
    protected $fileNameProperty;

    /**
     * @var  \Closure
     */
    protected $namer;


    /**
     * @var array
     */
    protected $namerParams;

    /**
     * @var  \Closure
     */
    protected $directoryNamer;

    /**
     * @var array
     */
    protected $directoryNamerParams;

    /**
     * @var array $mapping
     */
    protected $mapping;

    /**
     * @var string $mappingName
     */
    protected $mappingName;


    function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * Gets the reflection property that represents the
     * annotated property.
     *
     * @return \ReflectionProperty The property.
     */
/*    public function getProperty()
    {
        return $this->property;
    }*/

    /**
     * Sets the reflection property that represents the annotated
     * property.
     *
     * @param \ReflectionProperty $property The reflection property.
     */
    public function setProperty(\ReflectionProperty $property)
    {
        $this->property = $property;
        $this->property->setAccessible(true);
    }

    /**
     * Gets the reflection property that represents the property
     * which holds the file name for the mapping.
     *
     * @return \ReflectionProperty The reflection property.
     */
    /*   public function getFileNameProperty()
    {
        return $this->fileNameProperty;
    }*/


    public function useFileNamer($fileName)
    {
        if ($this->hasNamer()) {
            return call_user_func($this->namer, $this,$fileName, $this->namerParams);
        }
        return $fileName;

    }


    /**
     * Gets the configured upload directory.
     *
     * @return string The configured upload directory.
     */
    public function useDirectoryNamer($fileName, $clientOriginalName)
    {
        if ($this->hasDirectoryNamer()) {
            return call_user_func($this->directoryNamer, $this, $fileName, $clientOriginalName);
        }
        return $this->mapping['upload_dir'];
    }


    public function needResolveCollision()
    {
      return !$this->isOverwriteDuplicates();
    }


    public function resolveFileCollision($fileName, $clientOriginalName, $attempt = 1)
    {
       if ($this->hasNamer())  return  array (
               $this->useDirectoryNamer($fileName, $clientOriginalName),
               call_user_func(array ($this->namer[0],'resolveCollision'),$fileName, $attempt));

       throw new \Exception ('Filename resolving collision not supported (namer is empty)');
    }


    public function getUploadDir()
    {
        return $this->mapping['upload_dir'];
    }


    /**
     * Sets the reflection property that represents the property
     * which holds the file name for the mapping.
     *
     * @param \ReflectionProperty $fileNameProperty The reflection property.
     */
    public function setFileNameProperty(\ReflectionProperty $fileNameProperty)
    {
        $this->fileNameProperty = $fileNameProperty;
        $this->fileNameProperty->setAccessible(true);
    }

    /**
     * Gets the configured namer.
     *
     * @return \Closure
     */
/*    public function getNamer()
    {
        return $this->namer;
    }*/

    /**
     * Sets the namer.
     *
     */
    public function setNamer($namer, $method, $params)
    {
        $this->namer = array($namer, $method . 'Rename');
        $this->namerParams = $params;
    }

    /**
     * Determines if the mapping has a custom namer configured.
     *
     * @return bool True if has namer, false otherwise.
     */
    public function hasNamer()
    {
        return null !== $this->namer;
    }

    /**
     * Gets the configured directory namer.
     *
     * @return null|DirectoryNamerInterface The directory namer.
     */
/*    public function getDirectoryNamer()
    {
        return $this->directoryNamer;
    }*/

    /**
     * Sets the directory namer.
     */
    public function setDirectoryNamer($namer, $method, $params)
    {
        $this->directoryNamer = array($namer, $method . 'Rename');
        $this->directoryNamerParams = $params;
    }

    /**
     * Determines if the mapping has a custom directory namer configured.
     *
     * @return bool True if has directory namer, false otherwise.
     */
    public function hasDirectoryNamer()
    {
        return null !== $this->directoryNamer;
    }

    /**
     * Sets the configured configuration mapping.
     *
     * @param array $mapping The mapping;
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Gets the configured configuration mapping name.
     *
     * @return string The mapping name.
     */
    public function getMappingName()
    {
        return $this->mappingName;
    }

    /**
     * Sets the configured configuration mapping name.
     *
     * @param $mappingName The mapping name.
     */
    public function setMappingName($mappingName)
    {
        $this->mappingName = $mappingName;
    }

    /**
     * Gets the name of the annotated property.
     *
     * @return string The name.
     */
    public function getPropertyName()
    {
        return $this->property->getName();
    }

    /**
     * Gets the value of the annotated property.
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getPropertyValue()
    {
        return $this->property->getValue($this->obj);
    }


    public function setPropertyValue($value)
    {
        return $this->fileNameProperty->setValue($this->obj, $value);
    }


    /**
     * Gets the configured file name property name.
     *
     * @return string The name.
     */
    public function getFileNamePropertyName()
    {
        return $this->fileNameProperty->getName();
    }


    /**
     * Determines if the file should be deleted upon removal of the
     * entity.
     *
     * @return bool True if delete on remove, false otherwise.
     */
    public function getDeleteOnRemove()
    {
        return $this->mapping['delete_on_remove'];
    }


    public function isOverwriteDuplicates()
    {
        return $this->mapping['overwrite_duplicates'];
    }

    /**
     * Determines if the uploadable field should be injected with a
     * Symfony\Component\HttpFoundation\File\File instance when
     * the object is loaded from the datastore.
     *
     * @return bool True if the field should be injected, false otherwise.
     */
    public function getInjectOnLoad()
    {
        return $this->mapping['inject_on_load'];
    }

    /**
     * @return array
     */
    public function getDirectoryNamerParams()
    {
        return $this->directoryNamerParams;
    }

    public function getObj()
    {
        return $this->obj;
    }

    /**
     * @return array
     */
    public function getNamerParams()
    {
        return $this->namerParams;
    }
}