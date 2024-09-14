<?php

namespace Jasmin\Core\Helpers\Strings;

class Lorem
{
    public const IPSUM = <<<LOREM
    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
    voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
    sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
    anim id est laborum.
LOREM;

    private static ?array $latinWords = null;

    public static function title()
    {
        return self::sentence();
    }

    public static function sentence(int $wordLength = 7)
    {
        if (is_null(self::$latinWords)) {
            self::populateWords();
        }

        shuffle(self::$latinWords);

        return implode(" ", array_slice(self::$latinWords, 0, $wordLength)) . '.';
    }

    private static function populateWords()
    {
        self::$latinWords = explode(" ", preg_replace("/\s{2,}/", " ", preg_replace("/[^a-z ]/i", "", self::IPSUM)));
    }

    public static function paragraph(int $sentenceLength = 7)
    {
        $sentences = array_map([self::class, 'sentence'], range(1, $sentenceLength));

        return implode(" ", $sentences) . PHP_EOL;
    }

    public static function text(int $paragraphLength = 7)
    {
        $paragraphs = array_map([self::class, 'paragraph'], range(1, $paragraphLength));

        return implode(PHP_EOL, $paragraphs);
    }
}
