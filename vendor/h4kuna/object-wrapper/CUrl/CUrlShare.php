<?php

namespace h4kuna\CUrl;

use h4kuna\ObjectWrapper;

/**
 * PHP 5 >= 5.5.0
 *
 * @method bool setopt(int $option, string $value)
 */
class CurlShare extends ObjectWrapper {

    protected $prefix = 'curl_share_';

    public function __construct() {
        $this->resource = curl_share_init();
        curl_share_setopt($this->resource, CURLSHOPT_SHARE, CURL_LOCK_DATA_COOKIE);
    }

    public function close() {
        return curl_share_close($this->resource);
    }

}
