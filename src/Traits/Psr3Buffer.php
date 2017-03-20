<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 3/15/17
 * Time: 8:17 PM
 */

namespace Syndicate\Psr3Decorator\Traits;
use Closure;
use Syndicate\Psr3Decorator\Psr3Decorator;


/**
 * Class Psr3Buffer
 * Author: Shannon C
 * Born: 2017-03-15
 *
 * @package Syndicate\Psr3Decorator\Traits
 */
trait Psr3Buffer
{
    /** @var  array[] */
    private $buffers = array();

    /** @var  string[] */
    private $active_buffers = array();

    /** @var  Closure */
    private $buffer_filter;

    /** @var bool  */
    private $buffer_filter_registered = false;

    /**
     *  Begin/resume buffering for submitted buffer name
     *
     * Author: Shannon C
     *
     * @param $buffer_name
     * @return $this
     */
    public function startBuffer($buffer_name)
    {
        if (! $this instanceof Psr3Decorator) {
            return $this;
        }

        if (array_key_exists($buffer_name, $this->active_buffers)) {
            return $this;
        }

        $this->active_buffers[$buffer_name] = $buffer_name;

        if (! array_key_exists($buffer_name, $this->buffers)) {
            $this->buffers[$buffer_name] = array();
        }

        if ($this->buffer_filter_registered == false) {
            $this->addMessageFilter($this->getBufferMessageFilter(), -1000);
            $this->buffer_filter_registered = true;
        }
        return $this;
    } // end function startBuffer


    /**
     *  Stop buffering for a given buffer name
     *
     * Author: Shannon C
     *
     * @param $buffer_name
     *
     * @return $this
     */
    public function stopBuffer($buffer_name)
    {
        if (! $this instanceof Psr3Decorator) {
            return $this;
        }

        if (array_key_exists($buffer_name, $this->active_buffers)) {
            unset($this->active_buffers[$buffer_name]);
        }

        if (empty($this->active_buffers)) {
            $this->removeMessageFilter($this->getBufferMessageFilter());
            $this->buffer_filter_registered = false;
        }

        return $this;
    } // end function stopBuffer

    /**
     *  Return a buffer
     *
     * Author: Shannon C
     *
     * @param $buffer_name
     * @return array
     */
    public function getBuffer($buffer_name)
    {
        if (array_key_exists($buffer_name, $this->buffers)) {
            return $this->buffers[$buffer_name];
        }

        return array();
    } // end function getBuffer


    /**
     *  Clear a buffer
     *
     * Author: Shannon C
     *
     * @param $buffer_name
     * @return $this
     */
    public function clearBuffer($buffer_name)
    {
        if ($buffer_name == "*") {
            $this->buffers = array();
        } else {
            $this->buffers[$buffer_name] = array();
        }

        return $this;
    } // end function clearBuffer

    /**
     *  Get the filter closure
     *
     * Author: Shannon C
     * @return Closure
     */
    private function getBufferMessageFilter()
    {
        if (is_null($this->buffer_filter)) {
            $this->buffer_filter = function($message, $context) {
                $c = $this->applyContextFilters($message, $context);
                echo "buffer filter running\n";
                $m = $message . " " . json_encode($c);
                foreach($this->active_buffers as $buffer_name) {
                    if (! array_key_exists($buffer_name, $this->buffers)) {
                        $this->buffers[$buffer_name] = array();
                    }

                    $this->buffers[$buffer_name][] = $m;
                }

                return $message;
            };
        }

        return $this->buffer_filter;
    } // end function getBufferMessageFilter

} // end class Psr3Buffer
