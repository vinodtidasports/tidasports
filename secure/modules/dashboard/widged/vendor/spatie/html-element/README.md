# HtmlElement

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/html-element.svg?style=flat-square)](https://packagist.org/packages/spatie/html-element)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/html-element/master.svg?style=flat-square)](https://travis-ci.org/spatie/html-element)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/31d646ec-a754-471d-a2fd-1ec31843ba8b.svg?style=flat-square)](https://insight.sensiolabs.com/projects/31d646ec-a754-471d-a2fd-1ec31843ba8b)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/html-element.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/html-element)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/html-element.svg?style=flat-square)](https://packagist.org/packages/spatie/html-element)

HtmlElement is a library to make dynamic HTML rendering more managable. The syntax is based on [Hyperscript](https://github.com/dominictarr/hyperscript), and adds some [Emmet](http://emmet.io/)-style syntactic sugar too.

Elements are rendered using the static `HtmlElement::render` method (which I recommend wrapping in a plain function for readability).

```php
el('div.container > div.row > div.col-md-6',
    el('a', ['href' => '#'], 'Hello world!')
);
```
```html
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <a href="#">Hello world!</a>
    </div>
  </div>
</div>
```

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Usage

I recommend adding an `el` function to your application to improve readability over the static method.

```php
function el(string $tag, $attributes = null, $content = null) : string
{
    return \Spatie\HtmlElement\HtmlElement::render(...func_get_args());
}
```

## Examples

An empty tag:

```php
el('div');
```
```html
<div></div>
```

A plain tag with text contents:

```php
el('p', 'Hello world!');
```
```html
<p>Hello world!</p>
```

A tag with an attribute:

```php
el('p', ['style' => 'color: red;'], 'Hello world!');
```
```html
<p style="color: red;">Hello world!</p>
```

A tag with an ID set emmet-style:

```php
el('p#introduction', 'Hello world!');
```
```html
<p id="introduction">Hello world!</p>
```

A tag with an emmet-style ID and class:

```php
el('p#introduction.red', 'Hello world!');
```
```html
<p id="introduction" class="red">Hello world!</p>
```

A tag with emmet-style attributes:

```php
el('a[href=#][title=Back to top]', 'Back to top');
```
```html
<a href="#" title="Back to top">Back to top</a>
```

A more complex emmet-style abbreviation:

```php
el('div.container > div.row > div.col-md-6', 'Hello world!'));
```
```html
<div class="container">
  <div class="row">
    <div class="col-md-6">
      Hello world!
    </div>
  </div>
</div>
```

Limited support of [implicit tag names](https://docs.emmet.io/abbreviations/implicit-names/) (`div`s only):

```php
el('.container > .row > .col-md-6', 'Hello world!'));
```
```html
<div class="container">
  <div class="row">
    <div class="col-md-6">
      Hello world!
    </div>
  </div>
</div>
```

Manually nested tags:

```php
el('div', ['class' => 'container'],
    el('nav', ['aria-role' => 'navigation'], '...')
);
```
```html
<div class="container">
  <nav aria-role="navigation">...</nav>
</div>
```

Multiple children:

```php
el('ul', [el('li'), el('li')]);
```
```html
<ul>
  <li></li>
  <li></li>
</ul>
```

Self-closing tags:

```php
el('img[src=/background.jpg]');
```
```html
<img src="background.jpg">
```

```php
el('img', ['src' => '/background.jpg'], '');
```
```html
<img src="background.jpg">
```

## Arguments

The `el` function behaves differently depending on how many arguments are passed in.

#### `el(string $tag) : string`

When one argument is passed, only a tag will be rendered.

```php
el('p');
```
```html
<p></p>
```

---

#### `el(string $tag, string|array $contents) : string`

When two arguments are passed, they represent a tag and it's contents.

String example:

```php
el('p', 'Hello world!');
```
```html
<p>Hello world!</p>
```

Array example:

```php
el('ul', [el('li'), el('li')]);
```
```html
<ul>
  <li></li>
  <li></li>
</ul>
```

---

#### `el(string $tag, array $attributes, string|array $contents) : string`

When three arguments are passed, the first will be the tag name, the second an array of attributes, and the third a string or an array of contents.

```php
el('div', ['class' => 'container'],
    el('nav', ['aria-role' => 'navigation'], '...')
);
```
```html
<div>
  <nav aria-role="navigation">...</nav>
</div>
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
