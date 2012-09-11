<?php

namespace Iphp\FileStoreBundle\FileStorage;

use Iphp\FileStoreBundle\FileStorage\FileStorageInterface;
use Iphp\FileStoreBundle\Mapping\PropertyMapping;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * FileSystemStorage.
 *
 * @author Vitiko <vitiko@mail.ru>
 */
class FileSystemStorage implements FileStorageInterface
{

    protected $webDir;

    /**
     * Constructs a new instance of FileSystemStorage.
     *
     * @param
     */
    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }


    protected function getOriginalName(File $file)
    {
        return $file instanceof UploadedFile ?
            $file->getClientOriginalName() : $file->getFilename();
    }


    protected function getMimeType(File $file)
    {
        return $file instanceof UploadedFile ?
            $file->getClientMimeType() : $file->getMimeType();
    }


    public function   isSameFile(File $file, array $fileData)
    {
        return $file->getRealPath() == realpath($fileData['dir'] . '/' . $fileData['fileName']);
    }

    /**
     * {@inheritDoc}
     * File may be \Symfony\Component\HttpFoundation\File\File or \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function upload(PropertyMapping $mapping, File $file)
    {
        $originalName = $this->getOriginalName($file);
        $mimeType = $this->getMimeType($file);

        //transform filename and directory name if namer exists in mapping definition
        $fileName = $origName = $mapping->useFileNamer($originalName);
        list ($directoryName, $path) = $mapping->useDirectoryNamer($fileName, $originalName);

        //check if file already placed in needed position
        if (!$this->isSameFile($file, array('dir' => $directoryName, 'fileName' => $fileName))) {
            $try = 0;
            while ($mapping->needResolveCollision() && file_exists($directoryName . '/' . $fileName)) {

                if ($try > 15)
                    throw new \Exception ("Can't resolve collision for file  " . $directoryName . '/' . $origName);

                list ($directoryName, $path, $fileName) = $mapping->resolveFileCollision($origName,  $originalName, ++$try);
            }
            $file->move($directoryName, $fileName);
        }


        $fileData = array(
            'fileName' => $fileName,
            'originalName' => $originalName,
            'dir' => $directoryName,
            'mimeType' => $mimeType,
            'size' => filesize($directoryName . '/' . $fileName),
            'path' => $path
        );



       // print_r ($fileData);
      //  exit();

       if (!$fileData['path'])
       $fileData['path'] = substr($fileData['dir'], strlen($this->webDir)) . '/' . urlencode($fileName);


        // $fileData['url'] = $fileData['path'] . '/' . $fileData['fileName'];

        if (in_array($fileData['mimeType'], array('image/png', 'image/jpeg', 'image/pjpeg'))
            && function_exists('getimagesize')
        ) {


            list($width, $height, $type) = @getimagesize($fileData['dir'] . '/' . $fileData['fileName']);

            $fileData = array_merge($fileData, array(
                'width' => $width, 'height' => $height
            ));
        }

        return $fileData;
        // exit();

        // $mapping->getFileNameProperty()->setValue($obj, $name);


    }


    public function removeFile(array $fileData)
    {


        //var_dump ($fileData);

        @unlink($fileData['dir'] . '/' . $fileData['fileName']);
        return !file_exists($fileData['dir'] . '/' . $fileData['fileName']);
        //exit();
    }


    public function checkFileExists(array $fileData)
    {
        return file_exists($fileData['dir'] . '/' . $fileData['fileName']);
    }

    /**
     * {@inheritDoc}
     */
    public function removeByMapping(PropertyMapping $mapping)
    {

        if ($mapping->getDeleteOnRemove()) {
            $fileData = $mapping->getFileDataPropertyValue();

            if ($fileData && file_exists($fileData['dir'] . '/' . $fileData['fileName']))
                @unlink($fileData['dir'] . '/' . $fileData['fileName']);
        }

    }

    /**
     * ������������ � UploaderHelper
     * {@inheritDoc}
     */
    /*    public function resolvePath($obj, $field)
    {
        $mapping = $this->factory->fromField($obj, $field);
        if (null === $mapping) {
            throw new \InvalidArgumentException(sprintf(
                'Unable to find uploadable field named: "%s"', $field
            ));
        }

        return sprintf('%s/%s',
            $mapping->getUploadDir($obj, $field),
            $mapping->getFileNameProperty()->getValue($obj)
        );
    }*/
}
