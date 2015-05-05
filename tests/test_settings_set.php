<?php 

use \CloudyHills\Fest\FestJobOptions;


class FestOptionsTest extends PHPUnit_Framework_TestCase
{
    public function testReflexive()
    {
        $options = new FestJobOptions();

        // see full list of options below
        $options->set('async', True);
        $this->assertEquals(True, $options->get('async'));

    }

    // ...
}
