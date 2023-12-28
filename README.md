# TestMonitor TOPdesk Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/topdesk-client/v/stable)](https://packagist.org/packages/testmonitor/topdesk-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/topdesk-client.svg)](https://circleci.com/gh/testmonitor/topdesk-client)
[![Travis Build](https://travis-ci.com/testmonitor/topdesk-client.svg?branch=master)](https://app.travis-ci.com/github/topdesk-client)
[![Code Coverage](https://scrutinizer-ci.com/g/testmonitor/topdesk-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/testmonitor/topdesk-client/?branch=master)
[![Code Quality](https://scrutinizer-ci.com/g/testmonitor/topdesk-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/testmonitor/topdesk-client/?branch=master)
[![StyleCI](https://styleci.io/repos/223037352/shield)](https://styleci.io/repos/223037352)
[![License](https://poser.pugx.org/testmonitor/topdesk-client/license)](https://packagist.org/packages/testmonitor/topdesk-client)

This package provides a very basic, convenient, and unified wrapper for the TOPdesk REST API.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

To install the client you need to require the package using composer:

	$ composer require testmonitor/topdesk-client

Use composer's autoload:

```php
require __DIR__.'/../vendor/autoload.php';
```

You're all set up now!

## Usage

You'll have to instantiate the client using your credentials:

```php
$topdesk = new \TestMonitor\TOPdesk\Client('https://mytopdesk.topdesk.net', 'username', 'password');
```

Next, you can start interacting with TOPdesk.

## Examples

Get a list of incidents out of TOPdesk:

```php
$topdesk->incidents();
```

or create a new incident in TOPdesk:

```php
$incident = $topdesk->createIncident(new \TestMonitor\TOPdesk\Resources\Incident([
    'callerName' => 'John Doe',
    'callerEmail' => 'johndoe@testmonitor.com',
    'status' => 'firstLine',
    'number' => 'I1234',
    'briefDescription' => 'Some Request',
    'request' => 'Some Request Description'
]));
```

## Tests

The package contains integration tests. You can run them using PHPUnit.

    $ vendor/bin/phpunit

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

* **Thijs Kok** - *Lead developer* - [ThijsKok](https://github.com/thijskok)
* **Stephan Grootveld** - *Developer* - [Stefanius](https://github.com/stefanius)
* **Frank Keulen** - *Developer* - [FrankIsGek](https://github.com/frankisgek)
* **Muriel Nooder** - *Developer* - [ThaNoodle](https://github.com/thanoodle)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
