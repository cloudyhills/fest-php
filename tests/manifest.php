<?php 

use \CloudyHills\Fest\Manifest;


class FestManifestTest extends PHPUnit_Framework_TestCase
{
    public function testMakeObject() {
        $options = new Manifest();

        $this->assertTrue($options instanceof FestJobOptions);

        return $options;
    }
    
    /**
     * @expectedException \CLoudyHills\Fest\LogicException
     */
    public function testBadOption()
    {
        $options = new FestJobOptions();
        $options->set('blahblah', True);
    }

    public function testReflexive()
    {
        $values = [
            'async' => True,
            'recursive' => True,
            'path' => '.',
        ];
        $options = new FestJobOptions();

        foreach ($values as $option => $value) {
            $options->set($option, $value);
            $this->assertEquals($value, $options->get($option));
        }
    }

}
