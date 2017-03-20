<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:42 PM
 */

namespace Syndicate\Psr3Decorator\Traits;
use Closure;
use Syndicate\Psr3Decorator\Psr3Decorator;


/**
 * Trait Psr3Tagging
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3\Traits
 */
trait Psr3Tagging
{
    /** @var string[] */
    private $message_tags = array();

    /** @var string[] */
    private $context_tags = array();

    /** @var string[] */
    private $context_tag_keys = array();

    /** @var bool */
    private $message_filter_registered = false;

    /** @var Closure */
    private $message_filter;

    /** @var bool  */
    private $context_filter_registered = false;

    /** @var Closure */
    private $context_filter;

    //<editor-fold desc="Message Tags">
    /**
     *  Adds a tag to every log message processed
     *
     * Author: Shannon C
     *
     * @param $tag
     * @return $this
     */
    public function addMessageTag($tag)
    {
        if (! $this instanceof Psr3Decorator) {
            return $this;
        }

        $tag = strtoupper($tag);

        if (! in_array($tag, $this->message_tags)) {
            $this->message_tags[] = (string) $tag;
        }

        if ($this->message_filter_registered == false) {
            $this->addMessageFilter($this->getMessageFilterClosure());
            $this->message_filter_registered = true;
        }

        return $this;
    } // end function addMessageTag


    /**
     *  Removes an existing tag from any future log messages
     *
     * Author: Shannon C
     *
     * @param $tag
     * @return $this
     */
    public function removeMessageTag($tag)
    {
        $tag = strtoupper($tag);
        $key = array_search($tag, $this->message_tags);

        if ($key !== false) {
            unset($this->message_tags[$key]);
        }

        if (empty($this->message_tags)) {
            $this->removeMessageFilter($this->getMessageFilterClosure());
            $this->message_filter_registered = false;
        }

        return $this;
    } // end function removeMessageTag


    /**
     *  Return array of registered message tags
     *
     * Author: Shannon C
     *
     * @return \string[]
     */
    public function getMessageTags()
    {
        return $this->message_tags;
    } // end function getMessageTags


    /**
     *  Remove all registered message tags
     *
     * Author: Shannon C
     *
     */
    public function clearMessageTags()
    {
        $this->message_tags = array();

        return $this;
    } // end function clearMessageTags

    /**
     *  Generate the message filter closure which will add all registered tags to the message string
     *
     * Author: Shannon C
     *
     * @return Closure
     */
    private function getMessageFilterClosure()
    {
        if (is_null($this->message_filter)) {
            $this->message_filter = function($message, array $context){
                $tags = array_values($this->message_tags);
                $tags = json_encode($tags);
                $tags = str_replace('"','', $tags);
                $tags = "(TAGS:$tags) ";
                return $tags . $message;
            };
        }

        return $this->message_filter;
    } // end function getMessageFilterClosure

    //</editor-fold>

    //<editor-fold desc="Context Tags">

    /**
     *  Adds a key/value pair to all contexts that are logged
     *
     * Author: Shannon C
     *
     * @param $tag_key
     * @param $tag_value
     */
    public function addContextTag($tag_key, $tag_value)
    {
        if (! $this instanceof Psr3Decorator) {
            return $this;
        }

        $tag_key_upper = strtoupper($tag_key);

        if (array_key_exists($tag_key_upper, $this->context_tag_keys)) {
            return $this;
        }

        $this->context_tag_keys[$tag_key_upper] = $tag_key;
        $this->context_tags[$tag_key] = $tag_value;

        if ($this->context_filter_registered == false) {
            // add filter with priority of 100, so that it's run
            // relatively early
            $this->addContextFilter($this->getContextFilterClosure(), 100);
            $this->context_filter_registered = true;
        }

        return $this;
    } // end function addContextTag

    /**
     *  Remove a registered context key/value pair by key
     *
     * Author: Shannon C
     *
     * @param $tag_key
     * @return $this
     */
    public function removeContextTagKey($tag_key)
    {
        $tag_key_upper = strtoupper($tag_key);

        if (array_key_exists($tag_key_upper, $this->context_tag_keys) === false) {
            return $this;
        }

        $tag_key = $this->context_tag_keys[$tag_key_upper];

        if (array_key_exists($tag_key, $this->context_tags)) {
            unset($this->context_tags[$tag_key]);
        }

        if (empty($this->context_tags)) {
            $this->removeContextFilter($this->getContextFilterClosure());
            $this->context_filter_registered = false;
        }

        return $this;
    } // end function removeContextTagKey

    /**
     *  Get all registered context tags
     *
     * Author: Shannon C
     *
     * @return \string[]
     */
    public function getContextTags()
    {
        return $this->context_tags;
    } // end function getContextTags
    /**
     *  Remove all registered context tags
     *
     * Author: Shannon C
     *
     */
    public function clearContextTags()
    {
        $this->context_tags = array();
    } // end function clearContextTags
    /**
     *  Return the context filter method that combines registered tags with submitted context
     *  When duplicate keys are present in both arrays, the submitted context should be preserved
     *
     * Author: Shannon C
     *
     * @return Closure
     */
    private function getContextFilterClosure()
    {
        if (is_null($this->context_filter)) {
            $this->context_filter = function($message, array $context){
                return array_merge($this->context_tags, $context);
            };
        }

        return $this->context_filter;
    } // end function getContextFilterClosure
    //</editor-fold>

} // end trait Psr3Tagging
