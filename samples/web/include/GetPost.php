<?php
/**
 * @author rugk
 * @copyright Copyright (c) 2015 rugk
 * @license MIT
 */

/**
 * Returns the parameter from GET (preferred) or POST.
 *
 * @param $name The name of the parameter.
 * @return string|null
 */
function ReturnGetPost($name)
{
    if (isset($_GET[$name])) {
        return $_GET[$name];
    }
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }

    return null;
}
