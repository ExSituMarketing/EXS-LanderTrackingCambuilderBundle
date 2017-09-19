# EXS-LanderTrackingCambuilderBundle

[![Build Status](https://travis-ci.org/ExSituMarketing/EXS-LanderTrackingCambuilderBundle.svg)](https://travis-ci.org/ExSituMarketing/EXS-LanderTrackingCambuilderBundle)

## What is this bundle doing ?

This bundle is not a standalone bundle and requires `EXS-LanderTrackingHouseBundle`.

It will add an extracter and a formatter to be added to `EXS-LanderTrackingHouseBundle` to manage CamBuilder tracking parameter.

The extracter service searches for parameters :
- `AFNO` which contains a string composed of `{cmp}~{exid}`

The formatter service will add the parameters if  :
- `AFNO` will contains a string composed of `{cmp}~{exid}`

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

## Configuration

The `cmp` parameter has a default value configurable with this configuration key : 

```yml
# Default values.
exs_lander_tracking_cambuilder:
    default_cmp: 1
```

## Usage

Example :
```twig
    <a href="{{ 'http://www.test.tld/' | appendTracking('cambuilder') }}">Some link</a>
    <!-- Will generate : "http://www.test.tld/?AFNO=123~987654321" -->
    
    <a href="{{ 'http://www.test.tld/?foo=bar' | appendTracking('cambuilder') }}">Some link</a>
    <!-- Will generate : "http://www.test.tld?foo=bar&AFNO=123~987654321" -->
```

See `EXS-LanderTrackingHouseBundle`'s documentation for more information.
