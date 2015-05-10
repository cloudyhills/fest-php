<?php 

use \CloudyHills\Fest\Manifest;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class InitialtTest extends PHPUnit_Framework_TestCase
{
    public function testInitial() {
        $log = new Logger('debug');
        $log->pushHandler(new StreamHandler('php://STDOUT', Logger::DEBUG));


        
    }

}
