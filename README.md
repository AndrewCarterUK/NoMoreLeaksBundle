# NoMoreLeaksBundle - No More Memory Leaks

This bundle has been created to make it easier to run the Symfony framework in
production mode without memory leaks. It currently targets memory leaks in
Monolog and Doctrine.

For clarification, this software addresses memory leaks, not vegetable leeks. The
latter tends to be less of a problem for software developers.

## Install

Install with [composer](https://getcomposer.org):

```sh
composer require andrewcarteruk/nomoreleaksbundle
```

Add to `AppKernel.php`:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
          // ...
          new AndrewCarterUK\NoMoreLeaksBundle\NoMoreLeaksBundle(),
        );

        // ...
    }
// ...
```

## Configure

```yaml
no_more_leaks: ~
```

Which is the same as:

```yaml
no_more_leaks:
    doctrine: ~
    monolog: ~
```

Which is the same as:

```yaml
no_more_leaks:
    doctrine:
        enabled: true
        managers:
            - default
    monolog:
        enabled: true
        channels:
            - app
```
