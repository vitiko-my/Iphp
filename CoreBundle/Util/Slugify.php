<?php
namespace Iphp\CoreBundle\Util;


class Slugify
{


    static function slugifyPreserveWords($str, $minLen, $maxLen)
    {
        if (strlen($str) >= $maxLen) {
            $break = $minLen;

            for ($i = $maxLen; $i > $minLen; $i--) {
                if ($str[$i] == ' ' || $str[$i] == "\n") {
                    $break = $i;
                    break;
                }
            }

            $str =  substr($str, 0, $break);
        }
        return Translit::translit($str);
    }
}