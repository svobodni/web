<?php

namespace h4kuna;

use RuntimeException;

/**
 * Open Internet or Unix domain socket connection
 *
 * @author Milan Matějček
 */
class FSocket extends File {

    /** @var int */
    private $errno = 0;

    /** @var string */
    private $errstr;

    /**
     *
     * @param string $hostname
     * @param int $port
     * @param NULL|int $timeOut
     */
    public function __construct($hostname, $port = -1, $timeOut = NULL) {
        $this->resource = @fsockopen($hostname, $port, $this->errno, $this->errstr, $timeOut);
        if (!$this->resource) {
            $this->exception();
        }
    }

    /**
     * Error message
     *
     * @return string
     */
    public function getErrstr() {
        return $this->errstr;
    }

    /**
     * Error number
     *
     * @return int
     */
    public function getErrno() {
        return $this->errno;
    }

    /**
     * Error as string #number, message
     *
     * @return string
     */
    public function getError() {
        return "#{$this->errno}, " . $this->errstr;
    }

    /**
     * Error as exception
     *
     * @throws RuntimeException
     */
    protected function exception() {
        throw new RuntimeException($this->errstr, $this->errno);
    }

}
