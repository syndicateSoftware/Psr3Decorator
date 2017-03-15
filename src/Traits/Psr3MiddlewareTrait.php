<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 4:04 PM
 */

namespace Syndicate\DecoratedPsr3\Traits;
use Closure;


/**
 * Trait Psr3MiddlewareTrait
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3\Traits
 */
trait Psr3MiddlewareTrait
{
    /** @var Closure[] */
    private $message_filters = array();

    /** @var Closure[] */
    private $context_filters = array();

    protected function getMessageFilters()
    {
        return $this->message_filters;
    } // end function getMessageFilters

    protected function addMessageFilter($tag, Closure $closure)
    {
        if (! array_key_exists($tag, $this->message_filters)) {
            $this->message_filters[$tag] = $closure;
        }
    } // end function addMessageFilter

} // end trait Psr3MiddlewareTrait
