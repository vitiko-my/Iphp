<?php
namespace Iphp\FileStoreBundle\Naming;

/**
 *  @author Vitiko <vitiko@mail.ru>
 */

class DefaultNamer
{


    protected $obj;

    public function setObj($obj)
    {
        $this->obj = $obj;
        return $this;
    }

    public function getObj()
    {
        return $this->obj;
    }


    /**
     * Filename translitaration renamin
     * @param $name
     * @return string
     */
    function translitRename($name)
    {

        $name = preg_replace('/[^\\pL\d.]+/u', '-', $name);


        $iso = array(
            "Є" => "YE", "І" => "I", "Ѓ" => "G", "і" => "i", "№" => "N", "є" => "ye", "ѓ" => "g",
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D",
            "Е" => "E", "Ё" => "YO", "Ж" => "ZH",
            "З" => "Z", "И" => "I", "Й" => "J", "К" => "K", "Л" => "L",
            "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R",
            "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H",
            "Ц" => "C", "Ч" => "CH", "Ш" => "SH", "Щ" => "SHH", "Ъ" => "'",
            "Ы" => "Y", "Ь" => "", "Э" => "E", "Ю" => "YU", "Я" => "YA",
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
            "е" => "e", "ё" => "yo", "ж" => "zh",
            "з" => "z", "и" => "i", "й" => "j", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "",
            "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", "«" => "", "»" => "", "—" => "-"
        );
        $name = strtr($name, $iso);
        // trim
        $name = trim($name, '-');

        // transliterate
        if (function_exists('iconv')) {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }
        return strtolower($name);
    }


    /**
     * Rename file name based on value of object property (default: id)
     * @param $name
     * @param $params
     * @return string
     */
    function propertyRename($name, $params)
    {
        $field = isset($params['field']) && $params['field'] ? $params['field'] : 'id';
        $fieldValue = $this->obj->{'get' . ucfirst($field)}();
        if (!$fieldValue) $fieldValue = $this->obj->getId();
        if ($fieldValue) $name = $fieldValue . substr($name, strrpos($name, '.'));
        return $name;
    }


    // Разрешение коллизий с одинаковыми названиями файлов
    function resolveCollision($name, $attempt = 1)
    {

    }


}
