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
$handler = new StreamHandler("php://stdout", Logger::WARNING);

$monolog->pushHandler($handler);


$logger = new MyLogger($monolog);
$logger->redact("ARC");
$logger->redact("tag");
$logger->warning("Before tag", array("submitted" => "first"));
$logger->addMessageTag("Marco");
$logger->addMessageTag("Polo");
$logger->addContextTag("authenticated_user_id", 1234);
$logger->addContextTag("endpoint", "first");
$logger->warning("After tags");
$logger->removeMessageTag("Marco");
$logger->removeContextTagKey("ENDPOINT");
$logger->warning("After removing first tag(s)");
$logger->removeMessageTag("polo");
$logger->removeContextTagKey("AUTHENticated_User_ID");
$logger->warning("After removing all tags");

$logger->redact("abc");


