<p align="center">
  <a href="https://neilime.github.io/ansi-escapes-to-html" target="_blank"><img src="https://repository-images.githubusercontent.com/76674702/0f4fcf80-ece7-11e9-84f1-32287c3d8e95" width="400"></a>
  <h1 align="center">ANSI escapes to Html</h1>
</p>

[![Continuous integration](https://github.com/neilime/ansi-escapes-to-html/workflows/Continuous%20integration/badge.svg)](https://github.com/neilime/ansi-escapes-to-html/actions?query=workflow%3A%22Continuous+integration%22)
[![codecov](https://codecov.io/gh/neilime/ansi-escapes-to-html/branch/main/graph/badge.svg?token=eMuwgNub7Z)](https://codecov.io/gh/neilime/ansi-escapes-to-html)
[![Latest Stable Version](https://poser.pugx.org/neilime/ansi-escapes-to-html/v/stable)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Total Downloads](https://poser.pugx.org/neilime/ansi-escapes-to-html/downloads)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![License](https://poser.pugx.org/neilime/ansi-escapes-to-html/license)](https://packagist.org/packages/neilime/ansi-escapes-to-html)
[![Sponsor](https://img.shields.io/badge/%E2%9D%A4-Sponsor-ff69b4)](https://github.com/sponsors/neilime)

📢 **ANSI escapes to Html** is a php script that convert ANSI escapes (terminal formatting/color codes) to HTML markup:

```bash
\e[40;38;5;82m Hello \e[30;48;5;82m World
```

Became

```html
<span
  style="font-weight:normal;text-decoration:none;color:rgb(95,255,0);background-color:Black;"
>
  Hello
</span>
<span
  style="font-weight:normal;text-decoration:none;color:Black;background-color:rgb(95,255,0);"
>
  World
</span>
```

## Helping Project

❤️ If this project helps you reduce time to develop and/or you want to help the maintainer of this project. You can [sponsor](https://github.com/sponsors/neilime) him. Thank you !

## Contributing

👍 If you wish to contribute to this project, please read the [CONTRIBUTING.md](CONTRIBUTING.md) file. Note: If you want to contribute don't hesitate, I'll review any PR.

## Documentation

### [Installation](https://neilime.github.io/ansi-escapes-to-html/installation)

### [Usage](https://neilime.github.io/ansi-escapes-to-html/usage)

### [Code Coverage](https://codecov.io/gh/neilime/ansi-escapes-to-html)

### [PHP Doc](https://neilime.github.io/ansi-escapes-to-html/phpdoc)

### [Development](https://neilime.github.io/ansi-escapes-to-html/development)
