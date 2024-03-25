# Carty

A cart package for Laravel

---

**IMPORTANT NOTE** This package is still in development, so expect (major) changes along the way

---

## Requirements
- Min. Laravel 8
- Min. PHP 7.4 (supporting datatype type hinting)

Why support EoL/EoS software? There are still people using those packages for whatever reason :)

## Installation

Get the package
```
$ composer require reestyle-it/carty
```

Publish the config file
```
$ php artisan publish:vendor --provider="ReeStyleIT\Providers\CartyServiceProvider"
```

Add the serviceprovider to your app.php 
```
... 
[
    'providers' => [
        ...
        \ReeStyleIT\Carty::class,
    ],
],
...

```

Note: If you are on a Mac using Herd, you may alternatively use `herd php artisan` in stead of `php artisan`. 

## Important thing to take into consideration

## The cart does not care about your stock

If you set up a webshop, please always keep in mind that the webshop is always an independant part of your company. 
Sure, in the end it should decrease your available stock, but this software is not keeping track of this.

If you want to limit how many products your customer can add to the basket, that needs to be checked before using the 
cart functionality.

This is also the case when a customer wants to check out and you want to do a final check on it.

The software uses the "Macroable" trait, so you are able to add it yourself if wanted.

## Usage

### Macroable

Because both the cart class and the item class use the "Macroable" trait you can easily add your own functionalities.

### Class and used namespaces
```
use ReeStyleIT\Carty\Carty;
use ReeStyleIT\Carty\Carty\Item;
use Illuminate\Support\Collection;
```

### Basic usage

#### Adding items. Hash of an item will only be set ONCE.
```
$id = 'can-be-string-too';
$description = 'Any description you want';
$quantity = 1;
$item = Carty::addItem('item-id', 'Item description', $quantity);

echo $item->hash();
```

#### Retrieving all items
```
$quantity = 1;
Carty::addItem('item-id', 'Item description', $quantity); 

dump(Carty::items())
```

#### Get item hash
```
Carty::items()
    ->each(
        fn (Item $item) => echo $item->hash();
```

#### Removing an item
```
Carty::removeItem($hash);
```

### Adding and removing using models

#### Update config/carty.php

You can configure more than one model to 
```
'models' => [
    App\Model\SomeProduct::class => [
        'idField' => 'idProduct',
        'mapping' => [
            // leftside is item, rightside is model
            'id' => 'id',
            'description' => 'description',
            'options' => [
                'supplier' => 'product.supplier.name',
                'size' => 'product.size', 
            ],
        ],
    ],
],
```

#### Adding an item

```
$model = \App\Model\SomeProduct::find($idProduct);
$quantity = 1;

$item = Carty::addItemByModel($model, $quantity);

// Show some info of the object
dump($item->hash(), $item->getModelData());
```

#### Updating an item

```
$model = \App\Model\SomeProduct::find($idProduct);
$quantity = 1;

$item = Carty::updateItemByModel($model, $quantity);

// Show some info of the object
dump($item->hash(), $item->quantity());
```

#### Removing an item

```
$model = \App\Model\SomeProduct::find($idProduct);
$quantity = 1;

$item = Carty::removeItemByModel($model);
```
