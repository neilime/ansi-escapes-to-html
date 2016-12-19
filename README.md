# ANSI escapes to Html

[![Build Status](https://travis-ci.org/neilime/ansi-escapes-to-html.svg?branch=master)](https://travis-ci.org/neilime/ansi-escapes-to-html)
[![Latest Stable Version](https://poser.pugx.org/neilime/ansi-escapes-to-html/v/stable.svg)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Total Downloads](https://poser.pugx.org/neilime/ansi-escapes-to-html/downloads.svg)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Coverage Status](https://coveralls.io/repos/github/neilime/ansi-escapes-to-html/badge.svg?branch=master)](https://coveralls.io/github/neilime/ansi-escapes-to-html?branch=master)

_ANSI escapes to Html_ is a php script that convert ANSI escapes (terminal formatting/color codes) to HTML markup :
```bash
\e[40;38;5;82m Hello \e[30;48;5;82m World
````
Became
```html
<span style="font-weight:normal;text-decoration:none;color:rgb(95,255,0);background-color:Black;"> Hello </span>
<span style="font-weight:normal;text-decoration:none;color:Black;background-color:rgb(95,255,0);"> World </span>
```

# Helping Project

If this project helps you reduce time to develop and/or you want to help the maintainer of this project, you can make a donation, thank you.

<a href='https://pledgie.com/campaigns/33102'><img alt='Click here to lend your support to: ANSI escapes to Html and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/33102.png?skin_name=chrome' border='0' ></a>

# Contributing

If you wish to contribute to this project, please read the [CONTRIBUTING.md](CONTRIBUTING.md) file.
NOTE : If you want to contribute don't hesitate, I'll review any PR.

# Requirements

Name | Version
-----|--------
[php](https://secure.php.net/) | >=5.3.3

# Installation

## Main Setup

### With composer (the faster way)

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/ansi-escapes-to-html": "1.*@stable"
    }
    ```

2. Now tell composer to download __ANSI escapes to Html__ by running the command:

    ```bash
    $ php composer.phar update
    ```

### By cloning project (manual)

1. Clone this project into your `./vendor/` directory.

# Usage

## Composer autoloading

```php
// Composer autoloading
if (!file_exists($sComposerAutoloadPath = __DIR__ . '/vendor/autoload.php')) {
    throw new \RuntimeException('Composer autoload file "' . $sComposerAutoloadPath . '" does not exist');
}
if (false === (include $sComposerAutoloadPath)) {
    throw new \RuntimeException('An error occured while including composer autoload file "' . $sComposerAutoloadPath . '"');
}
```

## Initialize Highlighter

```php
$highlighter = new \AnsiEscapesToHtml\Highlighter();
```

## Convert ANSI to Html

```php
$ansiOutput = 'Default \e[34mBlue';
echo $highlighter->toHtml($ansiOutput);
```
Print : 
```html
 <span style="font-weight:normal;text-decoration:none;color:White;background-color:Black;">Default </span><span style="font-weight:normal;text-decoration:none;color:Blue;background-color:Black;">Blue</span>
```
