<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:40 PM
 */

require_once(__DIR__ . "/vendor/autoload.php");

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Syndicate\Psr3Decorator\Psr3Decorator;

// setup Monolog
$monolog = new Logger('PSR3DECORATOR');
$handler = new StreamHandler("php://stdout", Logger::INFO);
$monolog->pushHandler($handler);

/**
 * Example Custom Logger class
 *
 * Class MyLogger
 * Author: Shannon C
 *
 */
class MyLogger extends Psr3Decorator
{
    use \Syndicate\Psr3Decorator\Traits\Psr3Tagging;
    use \Syndicate\Psr3Decorator\Traits\Psr3Redaction;
    use \Syndicate\Psr3Decorator\Traits\Psr3Buffer;
    use \Syndicate\Psr3Decorator\Traits\Psr3MessageContextInterpolation;

    public function init()
    {
        $this->setMessageInterpolation(true);

        $this->addMessageFilter(function($message, $context){
            return strtoupper($message);
        }, -900);

        $this->addContextFilter(function($message, $context){
            $filtered_context = array();
            foreach ($context as $key => $value) {
                $upper_key = strtoupper($key);
                $upper_val = strtoupper($value);

                $filtered_context[$upper_key] = $upper_val;
            }

            return $filtered_context;
        }, -900);
    } // end function init
} // end class MyLogger



$user = array(
    "user_id"       =>  418,
    "password"      =>  "super_secret"
);


$logger = new MyLogger($monolog);
$logger->startBuffer("user_actions");
$logger->addMessageTag("M1");
$logger->addContextTag("user_id", $user['user_id']);
$logger->redact($user['password']);
$logger->warning("User { user_id  } logged in at " . date("Y-m-d H:i:s"), $user);
$logger->warning("user did this");
$logger->warning("user did that");
$logger->stopBuffer("user_actions");


$actions = $logger->getBuffer("user_actions");

echo "\n\n\nuser_actions buffer:\n";
print_r($actions);



