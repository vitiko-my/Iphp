<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */
namespace Iphp\FileStoreBundle\Naming;
use Iphp\FileStoreBundle\Mapping\PropertyMapping;

class DefaultDirectoryNamer
{


    /**
     *
     */
    function propertyRename(PropertyMapping $propertyMapping, $fileName, $clientOriginalName)
    {

        $params = $propertyMapping->getDirectoryNamerParams();
        $obj = $propertyMapping->getObj();
        $field = isset($params['field']) && $params['field'] ? $params['field'] : 'id';

        if (strpos($field, '.')) {
            $str = 'return $obj->get' . implode('()->get', array_map('ucfirst', explode('.', $field))) . '();';
            $fieldValue  = @eval ($str);
        }
        else $fieldValue = $obj->{'get' . ucfirst($field)}();

        if ($fieldValue) return $propertyMapping->getUploadDir() . '/' . $fieldValue;


        return $propertyMapping->getUploadDir();
    }


}
