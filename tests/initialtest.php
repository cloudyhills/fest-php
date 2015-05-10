<?php 

use CloudyHills\Fest\Manifest;
use CloudyHills\Fest\FileSys;
use CloudyHills\Fest\FestJobOptions;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class InitialTest extends PHPUnit_Framework_TestCase
{
    public function testInitial() {
        $log = new Logger('debug');
        $log->pushHandler(new StreamHandler('php://STDOUT', Logger::DEBUG));

        $options = new FestJobOptions($log);

        $filesys = new FileSys();

        $options->set('working_directory', '/home/chills/fest-php');
        $options->set('pathspec', '*');
        $options->set('name', 'test-backup');
        $options->set('recurse', True);
        $options->set('omit', ['vendor/*']);


        $manifest = new Manifest($log, $options, $filesys);

        $manifest->hydrate();
        var_dump($manifest->getData());

    }

}
