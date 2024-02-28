# Twig components extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/havit/twig-components.svg?style=flat-square)](https://packagist.org/packages/havit/twig-components)
[![Total Downloads](https://img.shields.io/packagist/dt/havit/twig-components.svg?style=flat-square)](https://packagist.org/packages/havit/twig-components)

This is a PHP package for automatically create Twig components as tags. This is highly inspired from Laravel Blade Components.

## Installation

You can install the package via Composer:

```bash
composer require havit/twig-components
```

## Configuration

This package work only with Silex and PHP 7.1

```php
$app['twig.options'] = [
    'debug'      => true,
    'cache'      => __DIR__.'/../storage/cache/twig',
    'components' => [
        'path'      => 'components',
        'namespace' => 'App\View',
    ],
];

$app->register(new \App\Provider\TwigComponentsServiceProvider());
```

To enable the package just pass your Twig environment object to the function and specify your components folder relative to your Twig templates folder.

### SILEX

Create Provider

```php
use Pimple\{Container, ServiceProviderInterface};

class TwigComponentsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->extend('twig', function (\Twig_Environment $twig, $app) {
            $twig->addExtension(new \Havit\TwigComponents\Extension\ComponentExtension($twig));
            $twig->setLexer(new \Havit\TwigComponents\Lexer\ComponentLexer($twig));

            return $twig;
        });
    }

}
```

## Usage

The components are just Twig templates in a folder of your choice (e.g. `components`) and can be used anywhere in your Twig templates. The slot variable is any
content you will add between the opening and the close tag.

### Named slots

```twig
{# /components/card.twig #}
<div {{ attributes.class('bg-white shadow p-3 rounded') }}>
    <h2 {{ title.attributes.class('font-bold') }}>
        {{ title }}
    </h2>
    <div>
        {{ body }}
    </div>
</div>

{# /index.twig #}
<x-card>
    <x-slot name="title" class="text-2xl">title</x-slot>
    <x-slot name="body">Body text</x-slot>
</x-card>
```

Also with the standard syntax.

```twig
{# /index.twig #}
{% x:card %}
    {% slot:title with {class: "text-2xl"} %}
        Title
    {% endslot %}
    {% slot:body %}
        Title
    {% endslot %}
{% endx %}
```

### Attributes

You can pass any attribute to the component in different ways. To interprate the content as Twig you need to prepend the attribute name with a `:` but it works
also in other ways.

snake-case variables will be converted to kebabCase

Call

```twig
<x-button 
    :class="'btn-' ~ 'success'"
    type="{{'this' ~ 'works' ~ 'too'}}" 
    action="or this"
    this="{{'this' ~ 'does'}}{{ 'not work' }}"
    data-key="foo"
>
    Submit
</x-button>
```

Component

```twig
<a href="{{ action }}" {{ attributes.class('btn btn-sm me-3') }} data-type="{{ type }}" data-key="{{ dataKey }}">
    {{ $slot }}
</a>
```

Compiled

```html
<a href="or this" class="btn btn-sm me-3 btn-success" data-type="thisworkstoo" data-key="foo">
    Submit
</a>
```

### Component Class

```php
<?php

namespace App\View;

use App\Application;

class Test extends \Havit\TwigComponents\View\Component
{

    public $title    = '';

    public function __construct(string $title = 'xyz')
    {
        $this->title = $title;
    }

    public function handle(Application $app)
    {
        // Do something
        $this->title = $app->trans($this->title);
    }

    public function template(): string
    {
        return 'components/test.twig';
    }
}

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
