<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:59 PM
 */

namespace Syndicate\Psr3Decorator\Traits;
use Closure;

/**
 * Trait Psr3Redaction
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3\Traits
 */
trait Psr3Redaction
{
    /** @var string[]  */
    private $redaction_items = array();

    /** @var Closure */
    private $redaction_message_filter;

    /** @var Closure */
    private $redaction_context_filter;

    /** @var bool  */
    private $filters_registered = false;

    /**
     *  Add a string to the array of redacted items
     *
     * Author: Shannon C
     *
     * @param $text
     * @return $this
     */
    public function redact($text)
    {
        if (! in_array($text, $this->redaction_items)) {
            $this->redaction_items[] = $text;
        }

        if ($this->filters_registered === false) {
            // register filters with a priority of -999 so that they run very early
            $this->addMessageFilter($this->getRedactionMessageFilter(), 999);
            $this->addContextFilter($this->getRedactionContextFilter(), 999);
            $this->filters_registered = true;
        }

        return $this;
    } // end function redact

    /**
     *  Return the filter used to redact messages
     *
     * Author: Shannon C
     *
     * @return Closure
     */
    private function getRedactionMessageFilter()
    {
        if (is_null($this->redaction_message_filter)) {
            $this->redaction_message_filter = function($message) {
                echo "redaction filter running \n";
                return str_replace($this->redaction_items, "*** REDACTED ***", $message);
            };
        }

        return $this->redaction_message_filter;
    } // end function getRedactionMessageFilter

    /**
     *  Return the filter used to redact context array
     *
     * Author: Shannon C
     *
     * @return Closure
     */
    private function getRedactionContextFilter()
    {
        if (is_null($this->redaction_context_filter)) {
            $this->redaction_context_filter = function($message, $context) {
                $json = json_encode($context);
                $redacted = str_replace($this->redaction_items, "*** REDACTED ***", $json);
                $arr = json_decode($redacted, true);

                return $arr;
            };
        }

        return $this->redaction_context_filter;
    } // end function getRedactionContextFilter
} // end trait Psr3Redaction
