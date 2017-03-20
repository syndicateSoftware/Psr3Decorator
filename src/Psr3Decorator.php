<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:27 PM
 */

namespace Syndicate\Psr3Decorator;
use Closure;
use Psr\Log\LoggerInterface;
use SplObjectStorage;

/**
 * Class Psr3Decorator
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3
 */
abstract class Psr3Decorator implements LoggerInterface
{
    /** @var  LoggerInterface */
    private $logger;

    /** @var  SplObjectStorage | Closure[] */
    private $message_filters;

    /** @var  SplObjectStorage | Closure[] */
    private $context_filters;

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
        $this->message_filters = new SplObjectStorage();
        $this->context_filters = new SplObjectStorage();
        $this->init();
    }

    /**
     * An over-writable function that will be called from the constructor, since
     * the constructor is final.  You can use this to do any initialization work, like
     * adding custom filters without importing them as traits
     *
     * Author: Shannon C
     *
     */
    protected function init()
    {

    } // end function init

    /**
     *
     *
     * Author: Shannon C
     *
     * @return LoggerInterface
     */
    public function getPsr3Implementation()
    {
        return $this->logger;
    } // end function getPsr3Implementation

    //<editor-fold desc="Psr3 Methods">
    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function emergency($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->emergency($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function alert($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->alert($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function critical($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->critical($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function error($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->error($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function warning($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->warning($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function notice($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->notice($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->info($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param string $message
     * @param array  $context
     */
    public function debug($message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->debug($filtered_message, $filtered_context);
    }

    /**
     *
     *
     * Author: Shannon C
     *
     * {@inheritdoc}
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array())
    {
        $filtered_message = $this->applyMessageFilters($message, $context);
        $filtered_context = $this->applyContextFilters($message, $context);
        $this->logger->log($level, $filtered_message, $filtered_context);
    }

    //</editor-fold>

    //<editor-fold desc="Message Filters">

    /**
     *  Apply all registered message filters to the submitted message
     *
     * Author: Shannon C
     *
     * @param $message
     *
     * @return mixed
     */
    protected function applyMessageFilters($message, $context)
    {
        foreach ($this->message_filters as $filter) {
            $message = $filter($message, $context);
        }

        return $message;
    } // end function applyMessageFilters


    /**
     *  Register a message filter function to be called whenever anything is logged
     *
     * Author: Shannon C
     *
     * @param Closure $filter
     */
    protected function addMessageFilter(Closure $filter, $priority = null)
    {

        if ($this->message_filters->contains($filter) == false) {
            $this->message_filters = $this->addFilterToPrioritizedStorage(
                $this->message_filters,
                $filter,
                $priority
            );
        }

    } // end function addMessageFilter

    /**
     *  Remove a message filter
     *
     * Author: Shannon C
     *
     * @param Closure $filter
     */
    protected function removeMessageFilter(Closure $filter)
    {
        if ($this->message_filters->contains($filter)) {
            $this->message_filters->detach($filter);
        }
    } // end function removeMessageFilter

    //</editor-fold>

    //<editor-fold desc="Context Filters">

    /**
     *  Apply all registered message filters to the submitted context
     *
     * Author: Shannon C
     *
     * @param $context
     *
     * @return mixed
     */
    protected function applyContextFilters($message, $context)
    {
        foreach ($this->context_filters as $filter) {
            $context = $filter($context, $message);
        }

        return $context;
    } // end function applyContextFilters
    /**
     *  Register a context filter function to be called whenever anything is logged
     *
     * Author: Shannon C
     *
     * @param Closure $filter
     */
    protected function addContextFilter(Closure $filter, $priority = null)
    {
        if ($this->context_filters->contains($filter) == false) {
            $this->context_filters = $this->addFilterToPrioritizedStorage(
                $this->context_filters,
                $filter,
                $priority
            );
        }
    } // end function addContextFilter

    /**
     *  Remove a context filter
     *
     * Author: Shannon C
     *
     * @param Closure $filter
     */
    protected function removeContextFilter(Closure $filter)
    {
        if ($this->context_filters->contains($filter)) {
            $this->context_filters->detach($filter);
        }
    } // end function removeContextFilter

    //</editor-fold>

    /**
     *  Inserts a filter into the SplObjectStorage supplied, at the proper place based on submitted priority.
     *  This function returns an SplObjectStorage object that has all filters added in the correct order.
     *  When iterated over, it should yield filters ordering by priority number (descending)
     *
     *  If two filters have the same priority, then they will be yielded in the order that they were added
     *
     * Author: Shannon C
     *
     * @param SplObjectStorage $s
     * @param Closure          $submitted_filter
     * @param                  $submitted_priority
     *
     * @return SplObjectStorage
     */
    private function addFilterToPrioritizedStorage(SplObjectStorage $s, Closure $submitted_filter, $submitted_priority = null)
    {
        // list = SplObjectStorage $s

        // if the supplied list is empty
        // then add it to the end with the submitted priority
        // no need to sort it
        if ($s->count() == 0) {
            $s->attach($submitted_filter, $submitted_priority);
            return $s;
        }

        if (is_null($submitted_priority)) {
            $submitted_priority = 0 ;
        }

        // we need to iterate through the
        // submitted list to make sure that all filters are in the correct order
        // to do this, we'll use a new list, and add to it, preserving order

        $ordered = new SplObjectStorage();

        // make sure that we're starting at the beginning of the list
        $s->rewind();

        while($s->valid()) {
            $current_filter_priority = $s->getInfo();

            if ($submitted_priority > $current_filter_priority && ! $ordered->contains($submitted_filter)) {
                $ordered->attach($submitted_filter, $submitted_priority);
            }

            $ordered->attach($s->current(), $current_filter_priority);

            $s->next();
        }

        // make sure we add the submitted filter, if it wasn't added in the loop above
        if ($ordered->contains($submitted_filter) === false) {
            $ordered->attach($submitted_filter, $submitted_priority);
        }

        return $ordered;
    } // end function addFilterToPrioritizedStorage

} // end class Psr3Decorator
