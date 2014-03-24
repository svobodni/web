<?php

namespace h4kuna\CUrl;

use Nette\StaticClassException;

/**
 * @author Milan Matějček
 */
class CurlBuilder {

    public function __construct() {
        throw new StaticClassException(__CLASS__);
    }

    /**
     * Prepare Curl for download page
     *
     * @param string $url
     * @return CUrl
     */
    static function createDownload($url) {
        $curl = new CUrl($url);
        $curl->setOptions(array(
            CURLOPT_HEADER => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE)
        );
        return $curl;
    }

    /**
     * Download content page
     *
     * @param string $url
     * @return string
     * @throws CUrlException
     */
    static function download($url) {
        try {
            $curl = self::createDownload($url);
            $content = $curl->exec();
            $curl->throwException();
            return $content;
        } catch (CUrlException $e) {
            if (!ini_get('allow_url_fopen')) {
                throw new CUrlException('You need allow_url_fopen -on or curl extension');
            }
        }
        return file_get_contents($url);
    }

    /**
     * EXPERIMENTAL METHODS ****************************************************
     * *************************************************************************
     */

    /**
     * @example
     * $content = array(
     *            'foo' => 'bar',
     *            'file' => array(
     *                  'content' => 'file content is simple text', // or path, mantatory
     *                  'name' => 'filename.txt', // optional
     *                  'type' => 'text/plain' // optional
     *            ));
     *
     * @param string $url
     * @param array $content
     * @return CUrl - call ->exec()
     */
    static function postUploadFile($url, array $content) {
        $eol = "\r\n";
        $boundary = md5(microtime(TRUE));
        $curl = new CUrl($url);
        $curl->setOptions(array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_POST => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTPHEADER => array('Content-Type: multipart/form-data; charset=utf-8; boundary="' . $boundary . '"'))
        );
        $body = '';
        foreach ($content as $name => $value) {
            $body .= '--' . $boundary . $eol;
            $body .= 'Content-Disposition: form-data;name="' . $name . '"';
            if (is_array($value)) {

                if (file_exists($value['content'])) {
                    $type = MimeTypeDetector::fromFile($value['content']);
                    $content = file_get_contents($value['content']);
                } else {
                    $type = MimeTypeDetector::fromString($content = $value['content']);
                }

                // is file
                $body .= '; filename="' . (isset($value['name']) ? $value['name'] : date('YmdHis')) . '"' . $eol;
                $body .= 'Content-Type: ' . (isset($value['type']) ? $value['type'] : $type) . $eol;

                if (preg_match('~base64~i', $content)) {
                    $body .= 'Content-Transfer-Encoding: base64' . $eol;
                    $content = preg_replace('~^base64~i', '', $content);
                }

                $body .= $eol;
                // $body .= chunk_split(base64_encode($content)); // RFC 2045
                $body .= trim($content) . $eol;
            } else {
                $body .= $eol . $eol . $value . $eol;
            }
        }
        $body .= "--$boundary--" . $eol . $eol;

        $curl->setopt(CURLOPT_POSTFIELDS, $body);
        return $curl;
    }

}
