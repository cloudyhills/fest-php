<?php 

use \CloudyHills\Fest\FestJobOptions;


class FestOptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \CLoudyHills\Fest\LogicException
     */
    public function testBadOption()
    {
        $options = new FestJobOptions();
        $options->set('blahblah', True);
    }

    public function testMakeObject() {
        $options = new FestJobOptions();

        $this->assertTrue($options instanceof FestJobOptions);

        return $options;
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
