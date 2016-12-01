# NoMoreLeaksBundle - No More Memory Leaks

[![Latest Stable Version](https://poser.pugx.org/andrewcarteruk/nomoreleaksbundle/v/stable)](https://packagist.org/packages/andrewcarteruk/nomoreleaksbundle)
[![Build Status](https://travis-ci.org/AndrewCarterUK/NoMoreLeaksBundle.svg?branch=master)](https://travis-ci.org/AndrewCarterUK/NoMoreLeaksBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AndrewCarterUK/NoMoreLeaksBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AndrewCarterUK/NoMoreLeaksBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/AndrewCarterUK/NoMoreLeaksBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AndrewCarterUK/NoMoreLeaksBundle/?branch=master)
[![License](https://poser.pugx.org/andrewcarteruk/nomoreleaksbundle/license)](https://packagist.org/packages/andrewcarteruk/nomoreleaksbundle)
[![Total Downloads](https://poser.pugx.org/andrewcarteruk/nomoreleaksbundle/downloads)](https://packagist.org/packages/andrewcarteruk/nomoreleaksbundle)

This bundle has been created to make it easier to run the Symfony framework in
production mode without memory leaks. It currently targets memory leaks in
Monolog and Doctrine.

For clarification, this software addresses memory leaks, not vegetable leeks. The
latter tends to be less of a problem for software developers.

By [AndrewCarterUK ![(Twitter)](http://i.imgur.com/wWzX9uB.png)](https://twitter.com/AndrewCarterUK)

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
