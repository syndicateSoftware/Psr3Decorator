<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 3/12/17
 * Time: 5:42 PM
 */
use Syndicate\Psr3Decorator\Psr3Decorator;

/**
 * Class MyLogger
 * Author: Shannon C
 * Born: 2017-03-12
 *
 */
class MyLogger extends Psr3Decorator
{
    use \Syndicate\Psr3Decorator\Traits\Psr3TaggableTrait;
    use \Syndicate\Psr3Decorator\Traits\Psr3RedactableTrait;
} // end class MyLogger
