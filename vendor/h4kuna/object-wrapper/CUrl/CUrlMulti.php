<?php

namespace h4kuna\CUrl;

use h4kuna\ObjectWrapper;

/**
 * @method string getContent()
 * @method int select(float $timeout) default 1.0
 * @method bool setOpt(int $option, mixed $value)
 * @method string strerror() PHP 5 >= 5.5.0
 */
class CurlMulti extends ObjectWrapper {

    protected $prefix = 'curl_multi_';

    /** @var array */
    private $handles = array();

    /** @var array */
    private $content = array();

    public function __construct() {
        $this->resource = curl_multi_init();
    }

    /**
     *
     * @param Curl $curl
     * @return void
     */
    public function removeHandles() {
        foreach ($this->handles as $curl) {
            $code = curl_multi_remove_handle($this->resource, $curl);
            $this->exception($code);
        }
        $this->handles = array();
    }

    /**
     *
     * @param CUrl $curl
     * @param string $name
     * @return int
     */
    public function addHandle(CUrl $curl, $name = NULL) {
        if ($name === NULL) {
            $name = $curl->getInfo('url');
        }
        $this->handles[$name] = $curl->getResource();
        return curl_multi_add_handle($this->resource, $curl->getResource());
    }

    /**
     *
     * @param array $args or list of Curl
     * @return void
     */
    public function addHandles($args /* , ... */) {
        foreach (func_get_args() as $val) {
            $name = NULL;
            if (is_array($val)) {
                list($name, $val) = each($val);
            }
            $this->addHandle($val, $name);
        }
    }

    private function exception($code) {
        if ($code) {
            $message = PHP_VERSION_ID >= 50500 ? $this->strerror() : "Error code: $code";
            throw new CUrlException($message);
        }
        return $code;
    }

    /**
     * @return bool
     */
    public function exec() {
        $this->content = array();
        $active = NULL;
        do {
            $mrc = curl_multi_exec($this->resource, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->resource) != -1) {
                do {
                    $mrc = curl_multi_exec($this->resource, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        return TRUE;
    }

    /**
     * Content of requests
     *
     * @param bool|string $join
     * @return array
     */
    public function getSelect($join = FALSE) {
        if ($this->content) {
            return $this->content;
        }

        foreach ($this->handles as $url => $resource) {
            $this->content[$url] = curl_multi_getcontent($resource);
        }

        if ($join === TRUE) {
            return implode($this->content);
        }
        if ($join === FALSE) {
            return $this->content;
        }
        return implode($join, $this->content);
    }

    public function close() {
        $this->removeHandles();
        return curl_multi_close($this->resource);
    }

}
