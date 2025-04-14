# monolog-wrapper

A wrapper around [monolog](https://github.com/Seldaek/monolog) that implements a few common standards, such as:
* A single folder path where log files are stored;
* Using dates (Y-m-d) as a filename;
* A pattern for log lines that prioritizes severity and time first.

while also letting you add as many streams and processors as you wish.

## Installation

```bash
composer require galvao-eti/monolog-wrapper
```

## Usage
```php
use GalvaoEti\MonologWrapper;

// If you wish to add streams and/or processors just add them to the static attributes:
MonologWrapper::$streams['streamName'] = 'streamSeverity';
MonologWrapper::$processors[] = new \Monolog\Processor\WebProcessor(null, [
    'ip' => 'REMOTE_ADDR',
    'referrer' => 'HTTP_REFERER',
    'method' => 'REQUEST_METHOD',
]);

$logger = MonologWrapper::getInstance();

// From here onwards just use Moolog's own methods, such as debug(), info(), etc...
```

## Credits
Made for [Galvão Desenvolvimento Ltda.](https://galvao.eti.br) by Er Galvão Abbott.
