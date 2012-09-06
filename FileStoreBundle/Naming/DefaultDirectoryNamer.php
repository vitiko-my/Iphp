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


        $fields = explode('/', $field);
        $path = '';


        foreach ($fields as $f) {


            if (strpos($f, '.')) {
                $str = 'return $obj->get' . implode('()->get', array_map('ucfirst', explode('.', $f))) . '();';
                $fieldValue = eval ($str);
            } else $fieldValue = $obj->{'get' . ucfirst($f)}();
            $path .= ($path ? '/' : '') . $fieldValue;
        }

        if ($path) return $propertyMapping->getUploadDir() . '/' . $path;


        return $propertyMapping->getUploadDir();
    }


    function dateRename(PropertyMapping $propertyMapping, $fileName, $clientOriginalName)
    {
        $params = $propertyMapping->getDirectoryNamerParams();
        $obj = $propertyMapping->getObj();

        $field = isset($params['field']) && $params['field'] ? $params['field'] : 'id';
        $depth = isset($params['depth']) && $params['depth'] ? strtolower($params['depth']) : 'day';

        $date = $obj->{'get'.ucfirst($field)}();
        $date = $date ?  $date->getTimestamp() : time();

        $tpl = "Y/m/d";
        if ($depth == 'month') $tpl = "Y/m";
        if ($depth == 'year') $tpl = "Y";

        $dirName = date ($tpl,  $date );

        return $propertyMapping->getUploadDir() . '/' . $dirName;
    }


}
