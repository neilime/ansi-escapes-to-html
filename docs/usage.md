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
<span
  style="font-weight:normal;text-decoration:none;color:White;background-color:Black;"
  >Default </span
><span
  style="font-weight:normal;text-decoration:none;color:Blue;background-color:Black;"
  >Blue</span
>
```
