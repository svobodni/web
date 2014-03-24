<?php

namespace h4kuna;

/**
 * iterate via line
 * @author Milan Matějček <milan.matejcek@gmail.com>
 */
class TextIterator extends \ArrayIterator {

    protected $csv = array(
        'active' => FALSE,
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\'
    );

    public function __construct($text) {
        parent::__construct($this->text2Array($text));
    }

    /**
     * active csv parser
     * @param bool|string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @return bool
     */
    public function setCsv($delimiter = TRUE, $enclosure = '"', $escape = '\\') {
        if (is_bool($delimiter)) {
            return $this->csv['active'] = $delimiter;
        }

        $this->csv = array(
            'active' => TRUE,
            'delimiter' => $delimiter,
            'enclosure' => $enclosure,
            'escape' => $escape
        );
        return TRUE;
    }

    public function current() {
        if ($this->csv['active']) {
            $content = parent::current();
            if ($content) {
                return str_getcsv($content, $this->csv['delimiter'], $this->csv['enclosure'], $this->csv['escape']);
            }
            return array();
        }
        return parent::current();
    }

    private function text2Array($text) {
        return is_array($text) ? $text : explode("\n", rtrim(str_replace("\r", '', $text)));
    }

}
