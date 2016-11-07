<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Utils;

use Doctrine\Common\Inflector\Inflector;

class Word
{
    /**
     * Get word in lower_case (for route names)
     *
     * @return string
     */
    public static function lowerCase($word, $plural = null)
    {
        return Inflector::tableize(static::applyPlural($word, $plural));
    }

    /**
     * Get word in CamelCase (for template directories)
     *
     * @return string
     */
    public static function camelCase($word, $plural = null)
    {
        return Inflector::classify(static::applyPlural($word, $plural));
    }

    /**
     * Get name in lower case (for url)
     *
     * @return string
     */
    public static function url($word, $plural = null)
    {
        return urlencode(str_replace('_', '-', static::lowerCase($word, $plural)));
    }

    /**
     * Apply plural rule
     *
     * @param string $word
     * @param boolean|null $plural
     *
     * @return string
     */
    public static function applyPlural($word, $plural = null)
    {
        if ($plural === null) {
            return $word;
        }

        return $plural ? Inflector::pluralize($word) : Inflector::singularize($word);
    }
}
