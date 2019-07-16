<?php namespace Tamtamchik\NameCase;

/**
 * Class Formatter.
 */
class Formatter
{
    // Irish exceptions.
    private const EXCEPTIONS = [
        '\bMacEdo'     => 'Macedo',
        '\bMacEvicius' => 'Macevicius',
        '\bMacHado'    => 'Machado',
        '\bMacHar'     => 'Machar',
        '\bMacHin'     => 'Machin',
        '\bMacHlin'    => 'Machlin',
        '\bMacIas'     => 'Macias',
        '\bMacIulis'   => 'Maciulis',
        '\bMacKie'     => 'Mackie',
        '\bMacKle'     => 'Mackle',
        '\bMacKlin'    => 'Macklin',
        '\bMacKmin'    => 'Mackmin',
        '\bMacQuarie'  => 'Macquarie',
    ];

    // General replacements.
    private const REPLACEMENTS = [
        '\bAl(?=\s+\w)'         => 'al',        // al Arabic or forename Al.
        '\b(Bin|Binti|Binte)\b' => 'bin',       // bin, binti, binte Arabic
        '\bAp\b'                => 'ap',        // ap Welsh.
        '\bBen(?=\s+\w)'        => 'ben',       // ben Hebrew or forename Ben.
        '\bDell([ae])\b'        => 'dell\1',    // della and delle Italian.
        '\bD([aeiou])\b'        => 'd\1',       // da, de, di Italian; du French; do Brasil
        '\bD([ao]s)\b'          => 'd\1',       // das, dos Brasileiros
        '\bDe([lrn])\b'         => 'de\1',      // del Italian; der/den Dutch/Flemish.
        '\bEl\b'                => 'el',        // el Greek or El Spanish.
        '\bLa\b'                => 'la',        // la French or La Spanish.
        '\bL([eo])\b'           => 'l\1',       // lo Italian; le French.
        '\bTe([rn])'            => 'te\1',      // ten/ter Dutch/Flemish
        '\bVan(?=\s+\w)'        => 'van',       // van German or forename Van.
        '\bVon\b'               => 'von',       // von Dutch/Flemish
    ];

    // Spanish conjunctions.
    private const CONJUNCTIONS = ["Y", "E", "I"];

    // Roman letters regexp.
    private const ROMAN_REGEX = '\b((?:[Xx]{1,3}|[Xx][Ll]|[Ll][Xx]{0,3})?(?:[Ii]{1,3}|[Ii][VvXx]|[Vv][Ii]{0,3})?)\b';

    // Default options.
    private static $options = [
        'lazy'    => true,
        'irish'   => true,
        'spanish' => true,
    ];

    /**
     * Main function for NameCase.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public static function nameCase(string $string = '', array $options = []): string
    {
        if ($string == '') return $string;

        self::$options = array_merge(self::$options, $options);

        // Do not do anything if string is mixed and lazy option is true.
        if (self::$options['lazy'] && self::skipMixed($string)) return $string;

        // Capitalize
        $string = self::capitalize($string);
        $string = self::updateIrish($string);

        // Fixes for "son (daughter) of" etc
        foreach (self::REPLACEMENTS as $pattern => $replacement) {
            $string = mb_ereg_replace($pattern, $replacement, $string);
        }

        $string = self::updateRoman($string);
        $string = self::fixConjunction($string);

        return $string;
    }

    /**
     * Capitalize first letters.
     *
     * @param string $string
     *
     * @return string
     */
    private static function capitalize(string $string): string
    {
        $string = mb_strtolower($string);

        $string = mb_ereg_replace_callback('\b\w', function ($matches) {
            return mb_strtoupper($matches[0]);
        }, $string);

        // Lowercase 's
        $string = mb_ereg_replace_callback('\'\w\b', function ($matches) {
            return mb_strtolower($matches[0]);
        }, $string);

        return $string;
    }

    /**
     * Skip if string is mixed case.
     *
     * @param string $string
     *
     * @return bool
     */
    private static function skipMixed(string $string): bool
    {
        $firstLetterLower = $string[0] == mb_strtolower($string[0]);
        $allLowerOrUpper  = (mb_strtolower($string) == $string || mb_strtoupper($string) == $string);

        return ! ($firstLetterLower || $allLowerOrUpper);
    }

    /**
     * Update for Irish names.
     *
     * @param string $string
     *
     * @return string
     */
    private static function updateIrish(string $string): string
    {
        if ( ! self::$options['irish']) return $string;

        if (mb_ereg_match('.*?\bMac[A-Za-z]{2,}[^aciozj]\b', $string) || mb_ereg_match('.*?\bMc', $string)) {
            $string = self::updateMac($string);
        }

        return mb_ereg_replace('Macmurdo', 'MacMurdo', $string);
    }

    /**
     * Fix Spanish conjunctions.
     *
     * @param string $string
     *
     * @return string
     */
    private static function fixConjunction(string $string): string
    {
        if ( ! self::$options['spanish']) return $string;

        foreach (self::CONJUNCTIONS as $conjunction) {
            $string = mb_ereg_replace('\b' . $conjunction . '\b', mb_strtolower($conjunction), $string);
        }

        return $string;
    }

    /**
     * Fix roman numeral names.
     *
     * @param string $string
     *
     * @return string
     */
    private static function updateRoman(string $string): string
    {
        return mb_ereg_replace_callback(self::ROMAN_REGEX, function ($matches) {
            return mb_strtoupper($matches[0]);
        }, $string);
    }

    /**
     * Updates irish Mac & Mc.
     *
     * @param string $string
     *
     * @return string
     */
    private static function updateMac(string $string): string
    {
        $string = mb_ereg_replace_callback('\b(Ma?c)([A-Za-z]+)', function ($matches) {
            return $matches[1] . mb_strtoupper(mb_substr($matches[2], 0, 1)) . mb_substr($matches[2], 1);
        }, $string);

        // Now fix "Mac" exceptions
        foreach (self::EXCEPTIONS as $pattern => $replacement) {
            $string = mb_ereg_replace($pattern, $replacement, $string);
        }

        return $string;
    }
}
