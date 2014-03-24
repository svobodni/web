<?php

namespace h4kuna;

/**
 * convert hash to array and array to hash
 *
 * @author Milan Matějček
 */
class Base64Array extends \ArrayIterator {

    private $check;

    /**
     * @param array|string $array
     */
    public function __construct($array, $check = 0) {
        if (is_string($array)) {
            $array = $this->decode($array);
        } elseif (!is_array($array)) {
            throw new \RuntimeException('Param must be array or string');
        }
        $this->check = $check;
        parent::__construct($array);
    }

    protected function encode(\ArrayIterator $array) {
        return base64_encode(serialize($array->getArrayCopy()));
    }

    protected function decode($s) {
        $base = @unserialize(base64_decode($s));
        if (!$base) {
            $base = array();
        }

        if ($this->check > 0 && strlen($base) > $this->check) {
            throw new \RuntimeException('Length of encode string is higger than is allowed. ' . $base);
        }

        return $base;
    }

    public function hash() {
        return $this->encode($this);
    }

    public function __toString() {
        return $this->hash();
    }

}
