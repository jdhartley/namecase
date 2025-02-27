# NameCase

[![Buy Me A Coffee][ico-coffee]][link-coffee]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Forenames and surnames are often stored either entirely in UPPERCASE or lowercase. This package allows you to convert names into the correct case where possible. Although forenames and surnames are typically stored separately if they do appear in a single string, whitespace-separated, NameCase deals correctly with them.  

Currently NameCase correctly name cases names which include any of the following:  

```
Mc, Mac, al, el, ap, bat, ben, bin, binti, binte, da, de, das, dos, delle, della, di, du, del, der, den, ten, ter, la, le, lo, van and von.
```

It correctly deals with names which contain apostrophes and hyphens too.

> **Warning!** This README.md is for version 2.0.x.  
> If you need PHP 5 compatible version, please use 1.0.x! [README.md](https://github.com/tamtamchik/namecase/blob/1.0.x/README.md#namecase)

## Install

Via Composer

```bash
$ composer require tamtamchik/namecase
```

## Usage

```php
use \Tamtamchik\NameCase\Formatter;

// As a static call
Formatter::nameCase("KEITH");                            // => Keith
Formatter::nameCase("LEIGH-WILLIAMS");                   // => Leigh-Williams
Formatter::nameCase("MCCARTHY");                         // => McCarthy
Formatter::nameCase("O'CALLAGHAN");                      // => O'Callaghan
Formatter::nameCase("ST. JOHN");                         // => St. John
Formatter::nameCase("VON STREIT");                       // => von Streit
Formatter::nameCase("AP LLWYD DAFYDD");                  // => ap Llwyd Dafydd
Formatter::nameCase("HENRY VIII");                       // => Henry VIII
Formatter::nameCase("VAN DYKE");                         // => van Dyke
Formatter::nameCase("JJ ABRAMS");                        // => JJ Abrams
Formatter::nameCase("PRINCE PHILIP, DUKE OF EDINBURGH"); // => Prince Philip, Duke of Edinburgh

// Or as an instance
$formatter = new Formatter();
$formatter->nameCase("LOUIS XIV");          // => Louis XIV

// Passing options
Formatter::setOptions([
  'lazy'        => true,
  'irish'       => true,
  'spanish'     => false,
  'roman'       => true,
  'hebrew'      => true,
  'postnominal' => true,
]);

// Or
$formatter = new Formatter(['spanish' => true]);

// Or 
$formatter->setOptions([
    'lazy' = false, 
    'postnominal' => false
]);

// Or even
Formatter::nameCase("VAN DYKE", ['lazy' = false]);
```

## Options

* `lazy` – Default: `true`. Do not do anything if string is already mixed case and lazy option is `true`.
* `irish` – Default: `true`. Correct "Mac" exceptions.
* `spanish` – Default: `false`. Correct `el, la` and spanish conjunctions.
* `roman` – Default: `true`. Correct roman numbers.
* `hebrew` – Default: `true`. Correct `ben, bat`.
* `postnominal` – Default: `true`. Correct post-nominal e.g. `PhD`.

## Global function

This package used to have global function `str_name_case`. Now it does not have it anymore.
But, if you still need to have it, use this snippet.

```php
<?php

use Tamtamchik\NameCase\Formatter;

if ( ! function_exists('str_name_case')) {

    /**
     * Wrapper for NameCase object to be used as global function.
     *
     * @param string $string  - string to NameCase.
     * @param array  $options - options for NameCase.
     *
     * @return string
     */
    function str_name_case($string, $options = [])
    {
        return Formatter::nameCase($string, $options);
    }
}

```

## Exclude Post-Nominals

```php
<?php

use Tamtamchik\NameCase\Formatter;

Formatter::excludePostNominals('MOst');

Formatter::nameCase('ČERNÝ MOST');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing & Demo

```bash
$ composer tests
$ composer demo
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security-related issues, please email <yuri.tam.tkachenko@gmail.com> instead of using the issue tracker.

## Acknowledgements

This library is a port of the [Perl library](https://metacpan.org/release/BARBIE/Lingua-EN-NameCase-1.19) and owes most of its functionality to the Perl version by Mark Summerfield.  
I also used some solutions from [Ruby version](https://github.com/tenderlove/namecase) by Aaron Patterson.  
Any bugs in the PHP port are my fault.

## Credits

Original PERL `Lingua::EN::NameCase` Version:

- Copyright &copy; Mark Summerfield 1998-2014. All Rights Reserved.
- Copyright &copy; Barbie 2014-2019. All Rights Reserved.

Ruby Version:

- Copyright &copy; Aaron Patterson 2006. All Rights Reserved.

PHP Version:

- [Yuri Tkachenko][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tamtamchik/namecase.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tamtamchik/namecase/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tamtamchik/namecase.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tamtamchik/namecase.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tamtamchik/namecase.svg?style=flat-square
[ico-coffee]: https://img.shields.io/badge/Buy%20Me%20A-Coffee-%236F4E37.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tamtamchik/namecase
[link-travis]: https://app.travis-ci.com/github/tamtamchik/namecase
[link-scrutinizer]: https://scrutinizer-ci.com/g/tamtamchik/namecase/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tamtamchik/namecase
[link-downloads]: https://packagist.org/packages/tamtamchik/namecase
[link-author]: https://github.com/tamtamchik
[link-contributors]: ../../contributors
[link-coffee]: https://www.buymeacoffee.com/tamtamchik
