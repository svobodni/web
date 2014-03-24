<?php

namespace h4kuna\CUrl;

use h4kuna\ObjectWrapper;
use Nette\Utils\MimeTypeDetector;

/**
 * @author Milan Matějček <milan.matejcek@gmail.com>
 * @method int errno()
 * @method string error()
 * @method string escape(string $str)
 * @method mixed exec()
 * @method int pause(int $bitmask) PHP5 >= 5.5.0
 * @method void reset() PHP5 >= 5.5.0
 * @method bool setOpt(int $option, mixed $value)
 * @method string strError(int $errornum) PHP 5 >= 5.5.0
 * @method string unescape(string $str) PHP 5 >= 5.5.0
 *
 */
class CUrl extends ObjectWrapper {

    const OPT = 'CURLOPT_';
    const INFO = 'CURLINFO_';

    protected $prefix = 'curl_';

    /** @var CurlShare */
    private static $share;

    /**
     * Inicializace curl
     *
     * @param string $url
     * @param array $options
     */
    public function __construct($url = NULL) {
        if (!extension_loaded('curl')) {
            throw new CUrlException('Curl extension, does\'t loaded.');
        }

        $this->resource = curl_init($url);
    }

    /**
     * Magic setter
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value) {
        $val = strtoupper($name);
        if (defined($val)) {
            return $this->setopt(constant($val), $value);
        }

        $const = self::OPT . $val;
        if (defined($const)) {
            return $this->setopt(constant($const), $value);
        }

        $const = self::INFO . $val;
        if (defined($const)) {
            return $this->setopt(constant($const), $value);
        }

        return parent::__set($name, $value);
    }

    /**
     * Magic getter
     *
     * @param mixed $name
     * @return mixed
     */
    public function &__get($name) {
        $val = strtoupper($name);
        if (defined(self::INFO . $val)) {
            $a = $this->getInfo(constant(self::INFO . $val));
            return $a;
        }
        return parent::__get($name);
    }

    /** @return void */
    public function close() {
        $this->shareClose();
        return curl_close($this->resource);
    }

    /**
     * Throw exception
     *
     * @return void
     */
    public function throwException() {
        if ($this->errno()) {
            throw new CUrlException($this->error(), $this->errno());
        }
    }

    /**
     * DECORATE API ************************************************************
     * *************************************************************************
     */

    /**
     *
     * @param int|string|NULL $opt
     * @return array|string|int
     */
    public function getInfo($opt = NULL) {
        if (is_int($opt)) {
            return curl_getinfo($this->resource, $opt);
        }
        $data = curl_getinfo($this->resource);
        if (array_key_exists($opt, $data)) {
            return $data[$opt];
        }
        return $data;
    }

    /**
     * PHP 5 >= 5.5.0
     *
     * @return resource
     */
    public function copyHandle() {
        return curl_copy_handle($this->resource);
    }

    /**
     * Prepare file for send
     *
     * @param string $filename
     * @param string $mimetype
     * @param string $postname
     * @return \CURLFile|string
     */
    public function fileCreate($filename, $mimetype = NULL, $postname = NULL) {
        if (PHP_VERSION_ID < 50500) {
            return '@' . $filename;
        }

        if ($mimetype === NULL) {
            $mimetype = MimeTypeDetector::fromFile($filename);
        }

        if ($postname === NULL) {
            $postname = basename($filename);
        }

        return curl_file_create($filename, $mimetype, $postname);
    }

    /**
     * Curl version
     *
     * @param int $age
     * @return string
     */
    public static function getVersion($age = CURLVERSION_NOW) {
        return curl_version($age);
    }

    /**
     * Set curl options
     *
     * @param array $opts
     * @return bool
     */
    public function setOptions(array $opts) {
        return curl_setopt_array($this->resource, $opts);
    }

    /**
     * SHARE *******************************************************************
     * *************************************************************************
     */

    /**
     * @return CurlShare
     */
    public function getShare() {
        if (self::$share === NULL) {
            self::$share = new CurlShare;
        }
        return self::$share;
    }

    /**
     * PHP 5 >= 5.5.0
     *
     * @return void
     */
    public function shareClose() {
        if (self::$share !== NULL) {
            self::$share->close();
            self::$share = NULL;
        }
    }

    /**
     *
     * @param int $option
     * @param string $value
     * @return CUrl
     */
    public function shareOption($option, $value) {
        $this->getShare()->setopt($option, $value);
        return $this;
    }

    /**
     * Enable share
     *
     * @return bool
     */
    public function enableShare(CurlShare $share = NULL) {
        if ($share === NULL) {
            $share = $this->getShare();
        }
        return curl_setopt($this->resource, CURLOPT_SHARE, $share->getResource());
    }

}
