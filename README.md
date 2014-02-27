Fractal Wrapper
=============

[![Build Status](https://travis-ci.org/BastianHofmann/fractal-magic.png?branch=master)](https://travis-ci.org/BastianHofmann/fractal-magic)

This package puts a nice wrapper around Phil Sturgeon's fractal package. It handles the nitty gritty task of spinning up the manager, creating a resource and provides a convient location to register your transformers. 

## Installation

Add this to your `composer.json` require:

```
"bastian/fractal-magic": "dev-master"
```

and run `composer update`.

## Usage

``` php
$fractal = new Hofmann\FractalMagic\Fractal([
  'resource' => new ResourceTransformer
]);

// Singular for a fractal item

$fractal->resource(['title' => 'Hello']);

// Plural for a fractal collection

$fractal->resources([
  ['title' => 'Hi!']
]);
```

Notice that you only have to set the singular resource binding.
This will return a Symfony response with the data in place, which you can return from your controller or route closure.

## Laravel Usage

For usage in laravel I suggest you register a `Response::marco` like this:

``` php
Response::macro('fractal', function()
{
  return new Hofmann\FractalMagic\Fractal([
    'resource' => new ResourceTransformer
  ]);
});
```

Now you can use this in your controller:

``` php
class SomeController {

  public function index()
  {
    return Response::fractal()->resources($data);
  }
  
}
```
