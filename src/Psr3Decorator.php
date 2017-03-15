<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:27 PM
 */

namespace Syndicate\Psr3Decorator;
use Psr\Log\LoggerInterface;
use Syndicate\DecoratedPsr3\Traits\Psr3MiddlewareTrait;


/**
 * Class Psr3Decorator
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3
 */
class Psr3Decorator implements LoggerInterface
{
    /** @var  LoggerInterface */
    protected $logger;

    /** @var  callable[] */
    protected $message_filters = array();


    /**
     * Author: Shannon C
     *
     * Psr3Decorator constructor.
     *
     * @param LoggerInterface $logger
     */
    public final function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
    }

    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
    }

    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
    }

    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
    }

    public function warning($message, array $context = array())
    {
        $filtered_message = $message;
        foreach ($this->message_filters as $filter) {
            $filtered_message = $filter($filtered_message);
        }

        $this->logger->warning($filtered_message, $context);
    }

    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
    }

    public function info($message, array $context = array())
    {
        $this->logger->info($message,$context);
    }

    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
    }

    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
    }

    protected function addMessageFilter($name, callable $filter)
    {
        if (array_key_exists($name, $this->message_filters)) {
            return;
        }

        $this->message_filters[$name] = $filter;

    } // end function addMessageFilter

    protected function removeMessageFilter($name)
    {
        if (array_key_exists($name, $this->message_filters)) {
            unset($this->message_filters[$name]);
        }
    } // end function removeMessageFilter
} // end class Psr3Decorator
