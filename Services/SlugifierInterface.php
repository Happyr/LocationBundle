<?php


namespace HappyR\LocationBundle\Services;


/**
 * Class SlugifierInterface
 *
 * @author Tobias Nyholm
 *
 */
interface SlugifierInterface
{
    /**
     * Slugify the $string
     *
     * @param $string
     *
     * @return mixed
     */
    public function slugify($string);
}