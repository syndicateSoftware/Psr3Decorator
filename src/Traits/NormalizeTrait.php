<?php
/**
 * Created by PhpStorm.
 * User: shannon
 * Date: 3/12/17
 * Time: 6:32 PM
 */

namespace Syndicate\Psr3Decorator\Traits;


/**
 * Trait NormalizeTrait
 * Author: Shannon C
 * Born: 2017-03-12
 *
 * @package Syndicate\Psr3Decorator\Traits
 */
trait NormalizeTrait
{
    protected function normalizeText($text)
    {
        $strip_characters = array(
            "_",
            " "
        );

        $lower = strtolower($text);
        $stripped = str_replace($strip_characters, "", $lower);

        return $stripped;
    } // end function normalizeText
} // end trait NormalizeTrait
