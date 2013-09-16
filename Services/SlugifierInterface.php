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
    public function slugify($name);
}