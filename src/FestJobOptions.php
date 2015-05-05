<?php

namespace CloudyHills\Fest;

class FestJobOptions {
    protected $async;
    // allowd values for type:
    // "boolean"
    // "integer"
    // "double" (for historical reasons "double" is returned in case of a float, and not simply "float")
    // "string"
    // "array"
    // "object"
    // "resource"
    protected $options = ['async', 'recursive', 'path'];
    protected $logger;

    function __construct($logger) {
        $this->logger = $logger;
    }

    public function set($option, $value) {
        if (False === ($i = array_search($option, $this->options)))
            throw new LogicException("Unknown option $option");

        // TODO do some type checking
        $this->$option = $value;
    }

    public function get($option) {
        if (False === ($i = array_search($option, $this->options)))
            throw new LogicException("Unknown option $option");

        return $this->$option;
    }

    public function sanitize() {
    }
}
