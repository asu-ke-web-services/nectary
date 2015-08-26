Nectary
=======

[![Build Status](https://travis-ci.org/gios-asu/nectary.svg)](https://travis-ci.org/gios-asu/nectary)

A simple PHP framework that is not tied to a web interface

Nectary is currently only available through GitHub, to install, add the following configuration to your `composer.json`:

```
{
    "repositories": [
        {
            "url": "https://github.com/gios-asu/nectary.git",
            "type": "git"
        }
    ],
    "require": {
        "gios-asu/nectary": "^0.0.1"
    }
}
```

## Developing

If you add a class or change a namespace, remember to
run `composer update` in order to add the class to the autoloader.

Check your code coverage with `vendor/bin/phpunit --coverage-html ./coverage`.