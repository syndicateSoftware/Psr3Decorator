<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:40 PM
 */

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/MyLogger.php");

$monolog = new Logger('PSR3DECORATOR');
$handler = new StreamHandler(__DIR__ . '/sc.log', Logger::WARNING);

$monolog->pushHandler($handler);


$logger = new MyLogger($monolog);
$logger->warning("Before tag");
$logger->addMessageTag("Marco");
$logger->addMessageTag("Polo");
$logger->warning("After tag");
$logger->removeMessageTag("Marco");
$logger->warning("After removing");

$logger->redact("abc");


