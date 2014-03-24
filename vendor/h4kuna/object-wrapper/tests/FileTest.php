<?php

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use h4kuna\File;
use PHPUnit_Framework_TestCase;

/**
 * @author Milan Matějček
 */
class FileTest extends PHPUnit_Framework_TestCase {

    public function testRead() {
        $fileName = __DIR__ . '/testRead.txt';
        $file = new File($fileName);
        $this->assertSame(file_get_contents($fileName), $file->read());
    }

}
