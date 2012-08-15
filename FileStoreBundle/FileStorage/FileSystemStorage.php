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

    /**
     * {@inheritDoc}
     */
    public function upload(PropertyMapping $mapping, UploadedFile $file)
    {
        //transform filename and directory name if namer exists in mapping definition
        $fileName = $origName =  $mapping->useFileNamer($file->getClientOriginalName());
        $uploadDir = $mapping->useDirectoryNamer($fileName, $file->getClientOriginalName());



        //print $uploadDir.'/'.$fileName.'--';
       // print file_exists($uploadDir.'/'.$fileName);
      //  exit();
        $try = 0;

        while ($mapping->needResolveCollision() && file_exists($uploadDir.'/'.$fileName))
        {
           if ($try > 15)
               throw new \Exception ("Can't resolve collision for file upload ".$uploadDir.'/'.$fileName );

           list ($uploadDir, $fileName) = $mapping->resolveFileCollision($origName, $try++);

        }

        $file->move($uploadDir, $fileName);

        $fileData = array(
            'fileName' => $fileName,
            'originalName' => $file->getClientOriginalName(),
            'dir' => str_replace('\\', '/', realpath($uploadDir)),
            'mimeType' => $file->getClientMimeType(),
            'size' => filesize($uploadDir . '/' . $fileName),
        );


        $fileData['path'] = substr($fileData['dir'], strlen($this->webDir));
        $fileData['url'] =  $fileData['path'].'/'.$fileData['fileName'];

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


    public function removeFile($fileData)
    {




        //var_dump ($fileData);

        @unlink($fileData['dir'] . '/' . $fileData['fileName']);
        return !file_exists($fileData['dir'] . '/' . $fileData['fileName']);
        //exit();
    }



    public function checkFileExists($fileData)
    {
        return file_exists($fileData['dir'] . '/' . $fileData['fileName']);
    }

    /**
     * {@inheritDoc}
     */
    public function removeByMapping(PropertyMapping $mapping)
    {

        if ($mapping->getDeleteOnRemove()) {
            $fileData= $mapping->getPropertyValue();

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
