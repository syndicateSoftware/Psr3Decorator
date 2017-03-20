<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 3/20/17
 * Time: 10:43 AM
 */

namespace Syndicate\Psr3Decorator\Traits;
use Closure;


/**
 * Trait Psr3MessageContextInterpolation
 * Author: Shannon C
 * Born: 2017-03-20
 *
 * @package Syndicate\Psr3Decorator\Traits
 */
trait Psr3MessageContextInterpolation
{
    /** @var  Closure */
    private $interpolation_filter;

    protected function setMessageInterpolation(bool $b)
    {
        if ($b) {
            $this->addMessageFilter($this->getMessageContextInterpolationFilter(), 990);
        } else {
            $this->removeMessageFilter($this->getMessageContextInterpolationFilter());
        }
    } // end function setMessageInterpolation

    /**
     *  Get the filter closure
     *
     * Author: Shannon C
     *
     * @return Closure
     */
    protected function getMessageContextInterpolationFilter()
    {
        if (is_null($this->interpolation_filter)) {
            $this->interpolation_filter = function($message, $context) {
                $context = $this->applyContextFilters($message, $context);

                // replace double braces with single ones
                $int = str_replace(array("{{","}}"), array("{","}"), $message);

                // strip out any whitespace after/before braces
                for ($i=0 ; $i<100 ; $i++) {
                    $int = str_replace(array("{ ", " }"), array("{", "}"), $int);
                }

                foreach ($context as $key => $value) {
                    $search = "{" . $key . "}";
                    $int = str_replace($search, $value, $int);
                }

                return $int;
            };
        }

        return $this->interpolation_filter;
    } // end function getMessageContextInterpolationFilter
} // end trait Psr3MessageContextInterpolation
