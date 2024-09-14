<?php

namespace Jasmin\Core\Helpers\Strings;

class Str
{
    public static function slugify(string $str)
    {
        $str = mb_strtolower($str);

        $map = [
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "yo",
            "ж" => "j",
            "з" => "z",
            "и" => "i",
            "й" => "j",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sh",
            "ъ" => "",
            "ь" => "",
            "ы" => "i",
            "э" => "e",
            "ю" => "ju",
            "я" => "ja",
        ];

        $unicodeChars = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);

        $slug = "";

        foreach ($unicodeChars as $unicodeChar) {
            if (array_key_exists($unicodeChar, $map)) {
                $slug .= $map[$unicodeChar];
            } else {
                $slug .= $unicodeChar;
            }
        }

        return preg_replace("/ /", "-", preg_replace("/\s{2,}/", " ", preg_replace("[^a-z ]", "", $slug)));
    }
}
