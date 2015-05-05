<?php

namespace CloudyHills\Fest;

class Manifest {
    protected $logger;
    protected $options;
    protected $filesys;
    
    function __construct($logger, $options, $filesys) {
        $this->logger = $logger;
        $this->options = $options;
        $this->filesys = $filesys;
    }

    
}
