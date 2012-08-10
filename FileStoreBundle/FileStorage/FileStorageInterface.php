<?php

namespace Iphp\FileStoreBundle\FileStorage;

use Iphp\FileStoreBundle\Mapping\PropertyMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * StorageInterface.
 *
 * @author Vitiko <vitiko@mail.ru>
 */
interface FileStorageInterface
{
    /**
     * Uploads the files in the uploadable fields of the
     * specified object according to the property configuration.
     *
     * @param object $obj The object.
     */
    public function upload(PropertyMapping $mapping, UploadedFile $file);

    /**
     * Removes the files associated with the object if configured to
     * do so.
     *
     * @param object $obj The object.
     */
    public function removeByMapping(PropertyMapping $mapping);

    public function removeFile($fileData);

    public function checkFileExists($fileData);

    /**
     * Resolves the path for a file based on the specified object
     * and field name.
     *
     * @param  object $obj   The object.
     * @param  string $field The field.
     * @return string The path.
     */
    //public function resolvePath($obj, $field);
}
