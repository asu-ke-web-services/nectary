![Nectary](documentation/images/nectary-with-text.png)

[![Build Status](https://travis-ci.org/gios-asu/nectary.svg)](https://travis-ci.org/gios-asu/nectary) [![Coverage Status](https://coveralls.io/repos/gios-asu/nectary/badge.svg?branch=develop&service=github)](https://coveralls.io/github/gios-asu/nectary?branch=develop) [![Code Climate](https://codeclimate.com/github/gios-asu/nectary/badges/gpa.svg)](https://codeclimate.com/github/gios-asu/nectary) [![GitHub issues](https://img.shields.io/github/issues/gios-asu/nectary.svg)]() [![GitHub license](https://img.shields.io/github/license/gios-asu/nectary.svg)]() [![Analytics](https://ga-beacon.appspot.com/UA-561868-49/gios-asu/nectary?flat)](https://github.com/igrigorik/ga-beacon)


A simple PHP framework that is not tied to a web interface.

## Installation

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
        "gios-asu/nectary": "dev-develop"
    }
}
```

## Developing

If you add a class or change a namespace, remember to
run `composer update` in order to add the class to the autoloader.

Check your code coverage with `vendor/bin/phpunit --coverage-html ./coverage`.
