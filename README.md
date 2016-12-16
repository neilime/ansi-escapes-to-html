# ANSI escapes to Html

[![Build Status](https://travis-ci.org/neilime/ansi-escapes-to-html.png?branch=master)](https://travis-ci.org/neilime/ansi-escapes-to-html)
[![Latest Stable Version](https://poser.pugx.org/neilime/ansi-escapes-to-html/v/stable.png)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Total Downloads](https://poser.pugx.org/neilime/ansi-escapes-to-html/downloads.png)](https://packagist.org/packages/neilime/ansi-escapes-to-html)

_ANSI escapes to Html_ is a php script that convert ANSI escapes (terminal formatting/color codes) to HTML markup : 
```bash
ESC[00mESC[01;34mbinESC[00m
ESC[01;34mcodeESC[00m
ESC[01;31mdropbox-lnx.x86-0.6.404.tar.gzESC[00m
ESC[00mfooESC[00m
````
Became 
```html
<span style="color:blue">bin</span>
<span style="color:blue">code</span>
<span style="color:red">dropbox-lnx.x86-0.6.404.tar.gz</span>
foo
```

# Helping Project

If this project helps you reduce time to develop and/or you want to help the maintainer of this project, you can make a donation, thank you.

<a href='https://pledgie.com/campaigns/'><img alt='Click here to lend your support to: ANSI escapes to Html and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/.png?skin_name=chrome' border='0' ></a>

# Contributing

If you wish to contribute to this project, please read the [CONTRIBUTING.md](CONTRIBUTING.md) file.
NOTE : If you want to contribute don't hesitate, I'll review any PR.

# Requirements

Name | Version
-----|--------
[php](https://secure.php.net/) | >=5.3.3

# Pages

1. [Installation](https://github.com/neilime/ansi-escapes-to-html/wiki/Installation)
2. [Exemple](https://github.com/neilime/ansi-escapes-to-html/wiki/Use-with-Zend-Skeleton-Application)
7. [PHP Doc](http://neilime.github.io/ansi-escapes-to-html/phpdoc/)
8. [Code Coverage](http://neilime.github.io/ansi-escapes-to-html/coverage/)

