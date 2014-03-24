<?php

namespace h4kuna;

use RuntimeException;

/**
 * This class is alias for SplFileObject
 *
 * @author Milan Matějček
 *
 * @method bool eof()
 * @method bool flush()
 * @method string getc()
 * @method array getcsv(int $length = 0, string $delimiter = ',', string $enclosure = '"', string $escape = '\\')
 * @method string gets(int $length)
 * @method string getss(int $length, string $allowable_tags)
 * @method int passthru()
 * @method int putcsv(array $fields, string $delimiter = ',', string $enclosure = '"')
 * @method int seek(int $offset, int $whence = SEEL_SET)
 * @method array stat()
 * @method int tell()
 * @method bool truncate(int $size)
 * @method int write(string $string, int $length = 0)
 */
class File extends ObjectWrapper {

    protected $prefix = 'f';

    /** @var string */
    private $fileName;

    /**
     *
     * @param string $fileName
     * @param string $mode
     * @param mixed $useIncludePath
     * @param mixed $context
     */
    public function __construct($fileName, $mode = 'r', $useIncludePath = FALSE, $context = NULL) {
        $this->fileName = $fileName;

        if ($context) {
            $this->resource = @fopen($fileName, $mode, $useIncludePath, $context);
        } else {
            $this->resource = @fopen($fileName, $mode, $useIncludePath);
        }

        if (!$this->resource) {
            throw new RuntimeException('This file "' . $fileName . '" did not open.');
        }
    }

    /**
     *
     * @param int $operation
     * @param int $wouldLock
     * @return bool
     */
    public function lock($operation, &$wouldLock = NULL) {
        return flock($this->resource, $operation, $wouldLock);
    }

    /**
     *
     * @param int $length
     * @return string
     */
    public function read($length = 0) {
        if ($length === 0) {
            $length = filesize($this->fileName);
        }
        return fread($this->resource, $length);
    }

    /**
     * Close resourse
     * @return bool
     */
    public function close() {
        return fclose($this->resource);
    }

}
