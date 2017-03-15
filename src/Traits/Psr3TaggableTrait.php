<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 2/21/17
 * Time: 3:42 PM
 */

namespace Syndicate\Psr3Decorator\Traits;
use Syndicate\Psr3Decorator\Psr3Decorator;


/**
 * Trait Psr3TaggableTrait
 * Author: Shannon C
 * Born: 2017-02-21
 *
 * @package Syndicate\DecoratedPsr3\Traits
 */
trait Psr3TaggableTrait
{
    /** @var string[] */
    private $message_tags = array();

    /** @var string[] */
    private $context_tags = array();

    /** @var bool */
    private $message_filter_registered = false;


    /**
     *  Adds a tag to every log message processed
     *
     * Author: Shannon C
     *
     * @param $tag
     */
    public function addMessageTag($tag)
    {
        if (! $this instanceof Psr3Decorator) {
            return ;
        }

        $tag = strtoupper($tag);

        if (! in_array($tag, $this->message_tags)) {
            $this->message_tags[] = (string) $tag;
        }

        if ($this->message_filter_registered) {
            return;
        }

        $this->addMessageFilter("TAGGABLE", array($this, "filterMessage"));
        $this->message_filter_registered = true;
    } // end function addMessageTag


    /**
     *  Removes an existing tag from any future log messages
     *
     * Author: Shannon C
     *
     * @param $tag
     */
    public function removeMessageTag($tag)
    {
        $tag = strtoupper($tag);
        $key = array_search($tag, $this->message_tags);

        if ($key !== false) {
            unset($this->message_tags[$key]);
        }

        if (empty($this->message_tags)) {
            $this->removeMessageFilter("TAGGABLE");
            $this->message_filter_registered = false;
        }
    } // end function removeMessageTag

    /**
     *  Callable that actually adds registered tags to log messages
     *
     * Author: Shannon C
     *
     * @param $msg
     *
     * @return string
     */
    protected function filterMessage($msg)
    {
        $tags = array_values($this->message_tags);
        $tags = json_encode($tags);
        $tags = str_replace('"','', $tags);
        $tags = "(TAGS:$tags) ";
        return $tags . $msg;
    } // end function filterMessage

    public function addContextTag($tag_key, $tag_value)
    {

    } // end function addContextTag

} // end trait Psr3TaggableTrait
