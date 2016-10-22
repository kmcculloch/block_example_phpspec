<?php

namespace Drupal\block_example_phpspec;

/**
 * @class DrupalServiceWrapper
 */
class DrupalServiceWrapper
{
    /**
     * Call a function from the global PHP namespace.
     *
     * @param string $func
     *   The name of the function.
     * @param array  $args
     *   Arguments to pass to the function.
     *
     * @return mixed
     */
    public function call($func, $args = array())
    {
        return call_user_func_array($func, $args);
    }
}

// vim: set ft=php.symfony :
