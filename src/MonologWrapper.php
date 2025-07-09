<?php

/**
 * GalvaoEti\MonologWrapper
 * A wrapper around Monolog that implements a few standards and provides a decent level of customization
 *
 * @author Er Galvão Abbott <galvao@php.net>
 * @version 0.1.0
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 *
 * @link https://github.com/galvao-eti/monolog-wrapper
 * @link https://packagist.org/packages/galvao-eti/monolog-wrapper
 */

declare(strict_types = 1);

namespace MonologWrapper;

use \DateTime;
use \Exception;

use Monolog\{
    Formatter\LineFormatter,
    Handler\StreamHandler,
    Level,
    Logger,
};

class MonologWrapper
{
    public static ?string $path = null;
    public static ?Logger $instance = null;

    public static array $streams = [
        'default' => Level::Debug,
    ];

    public static array $processors = [];

    public static function getInstance(string $path, string $name): Logger
    {
        self::$path = realpath($path);

        if (self::$path === false) {
            throw new Exception(self::$path . ' is not a valid directory.');
        }

        if (self::$instance === null) {
            self::$instance = new Logger($name);

            foreach (self::$processors as $processor) {
                self::$instance->pushProcessor($processor);
            }

            array_map(function ($streamName, $streamLevel) {
                if ($streamName !== 'default') {
                    self::$path .= '/' . $streamName;
                }

                if (!is_writable(self::$path)) {
                    throw new Exception(self::$path . ' is not writable.');
                }

                $time = new DateTime();
                $logFile = $time->format('Y-m-d') . '.log';

                $timeFormat = 'H:i:s';
                $lineFormat = "[ %level_name% ] - %datetime%: %message%\nDATA:\n%extra%\n\n";

                $formatter = new LineFormatter($lineFormat, $timeFormat);

                $stream = new StreamHandler(self::$path . '/' . $logFile, $streamLevel);
                $stream->setFormatter($formatter);

                self::$instance->pushHandler($stream);
            }, array_keys(self::$streams), array_values(self::$streams));
        }

        return self::$instance;
    }
}
