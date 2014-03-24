<?php

namespace h4kuna;

/**
 * @see http://www.php.net/manual/en/ref.ssh2.php
 * use method without prefix ssh2_
 *
 * @example
 * $ssh = new SSH2('example.com');
 * $ssh->auth_password('username', 'password'); // call ssh2_auth_password($resource, $username, $passwords)
 * echo $ssh->cli('ls');
 */
class SSH2 extends ObjectWrapper {

    protected $prefix = 'ssh2_';

    public function __construct($host, $port = 22, array $methods = NULL, array $callbacs = NULL) {
        $this->resource = ssh2_connect($host, $port, $methods, $callbacs);
    }

    /**
     * Run command
     *
     * @param string $command
     * @return string
     */
    public function cli($command) {
        $s = $this->exec($command);
        stream_set_blocking($s, true);
        return stream_get_contents($s);
    }

    /**
     * Set default 2. param
     *
     * @param stream $stream
     * @param int $streamid
     * @return stream
     */
    public function fetch_stream($stream, $streamid = SSH2_STREAM_STDERR) {
        return ssh2_fetch_stream($stream, $streamid);
    }

    public function close() {
        $this->resource = NULL;
    }

}
