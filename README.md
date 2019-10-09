# ANSI escapes to Html

[![Build Status](https://travis-ci.org/neilime/ansi-escapes-to-html.svg?branch=master)](https://travis-ci.org/neilime/ansi-escapes-to-html)
[![Latest Stable Version](https://poser.pugx.org/neilime/ansi-escapes-to-html/v/stable.svg)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Total Downloads](https://poser.pugx.org/neilime/ansi-escapes-to-html/downloads.svg)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Coverage Status](https://coveralls.io/repos/github/neilime/ansi-escapes-to-html/badge.svg?branch=master)](https://coveralls.io/github/neilime/ansi-escapes-to-html?branch=master)
[![Beerpay](https://beerpay.io/neilime/ansi-escapes-to-html/badge.svg)](https://beerpay.io/neilime/ansi-escapes-to-html)

📢 __ANSI escapes to Html__ is a php script that convert ANSI escapes (terminal formatting/color codes) to HTML markup:
```bash
\e[40;38;5;82m Hello \e[30;48;5;82m World
````
Became
```html
<span style="font-weight:normal;text-decoration:none;color:rgb(95,255,0);background-color:Black;"> Hello </span>
<span style="font-weight:normal;text-decoration:none;color:Black;background-color:rgb(95,255,0);"> World </span>
```

# Helping Project

❤️ If this project helps you reduce time to develop and/or you want to help the maintainer of this project, you can support him on [![Beerpay](https://beerpay.io/neilime/ansi-escapes-to-html/badge.svg)](https://beerpay.io/neilime/ansi-escapes-to-html) Thank you !

# Contributing

👍 If you wish to contribute to this project, please read the [CONTRIBUTING.md](CONTRIBUTING.md) file. Note: If you want to contribute don't hesitate, I'll review any PR.

# Documentation

1. [Installation](https://github.com/neilime/ansi-escapes-to-html/wiki/Installation)
2. [Usage](https://github.com/neilime/ansi-escapes-to-html/wiki/Usage)
3. [Code Coverage](https://coveralls.io/github/neilime/ansi-escapes-to-html)
4. [PHP Doc](https://neilime.github.io/ansi-escapes-to-html/phpdoc)
