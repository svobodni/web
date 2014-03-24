<?php

namespace h4kuna;

use Nette\Object;
use RuntimeException;

interface IObjectWrapper {

    /**
     * Close opened resourcem called in __destructor
     */
    public function close();
}

/**
 * @author Milan Matějček
 */
abstract class ObjectWrapper extends Object implements IObjectWrapper {

    /** @var resource */
    protected $resource;

    /** @var string prefix of function */
    protected $prefix;

    public function __call($name, $args = array()) {
        $fname = $this->prefix . $name;
        if (function_exists($fname)) {
            array_unshift($args, $this->resource);
            return call_user_func_array($fname, $args);
        }
        throw new RuntimeException('Call undefined method ' . get_called_class() . '::' . $name);
    }

    /**
     * Active resource of class
     *
     * @return Resource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * Safety clear resource
     *
     * @return void
     */
    public function clearResource() {
        if (!is_resource($this->resource)) {
            $this->resource = NULL;
            return;
        }

        $this->close(); //magic call or implement
        $this->resource = NULL;
    }

    public function __destruct() {
        $this->clearResource();
    }

}
