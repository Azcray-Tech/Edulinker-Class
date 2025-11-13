<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// Create the logger
$logger = new Logger('EdulinkerClass');

// Handler for writing logs to a file
$fileHandler = new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG);

// The default format is too verbose, so we'll create a custom one.
$output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
$formatter = new LineFormatter($output);

// Set the formatter for the handler
$fileHandler->setFormatter($formatter);

// Add the handler to the logger
$logger->pushHandler($fileHandler);

// Optionally, add a handler for critical errors to be emailed
// $emailHandler = new SwiftMailerHandler(Swift_Message::newInstance('Critical Error'), $mailer, Logger::CRITICAL);
// $logger->pushHandler($emailHandler);

return $logger;
