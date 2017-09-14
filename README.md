# EXS-LanderTrackingCambuilderBundle

## What is this bundle doing ?

This bundle is not a standalone bundle and requires `EXS-LanderTrackingHouseBundle`.

It will add an extracter and a formatter to be added to `EXS-LanderTrackingHouseBundle` to manage CamBuilder tracking parameter.

## Installation

Download the bundle using composer

```
$ composer require exs/lander-tracking-awe-bundle
```

Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new EXS\LanderTrackingCambuilderBundle\EXSLanderTrackingCambuilderBundle(),
        // ...
    );
}
```

## Usage

See `EXS-LanderTrackingHouseBundle`'s documentation for more information.