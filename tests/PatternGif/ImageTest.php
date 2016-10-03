<?php

class ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldGenerateOneBlackBoxWithoutMargin()
    {
        $image = new \PatternGif\Image([[0]]);
        $image->setElementMargin(0);

        $this->assertPng(__DIR__ . '/images/1_black_box_30x30x0.png', $image);
    }

    /**
     * @test
     */
    public function shouldGenerateOneBlackBoxWithMargin()
    {
        $image = new \PatternGif\Image([[0]]);
        $image->setElementMargin(5);

        $this->assertPng(__DIR__ . '/images/1_black_box_30x30x5.png', $image);
    }

    /**
     * @test
     */
    public function shouldGenerateOneBlackBoxWithUserElementHeight()
    {
        $image = new \PatternGif\Image([[0]]);
        $image->setElementHeight(50);
        $image->setElementMargin(0);

        $this->assertPng(__DIR__ . '/images/1_black_box_50x30x0.png', $image);
    }

    /**
     * @test
     */
    public function shouldGenerateOneBlackBoxWithUserElementWidth()
    {
        $image = new \PatternGif\Image([[0]]);
        $image->setElementWidth(50);
        $image->setElementMargin(0);

        $this->assertPng(__DIR__ . '/images/1_black_box_30x50x0.png', $image);
    }

    /**
     * @test
     */
    public function shouldGenerateOneRedBoxWithUserColor()
    {
        $image = new \PatternGif\Image([[2]]);
        $image->setElementMargin(0);

        $image->addColor(2, new \PatternGif\Color(255, 0, 0));

        $this->assertPng(__DIR__ . '/images/1_red_box_30x30x0.png', $image);
    }

    /**
     * @test
     */
    public function shouldGenerateOneBlackBoxWithUserBackground()
    {
        $image = new \PatternGif\Image([[0]]);
        $image->setBackgroundColor(new \PatternGif\Color(255, 0, 0));

        $this->assertPng(__DIR__ . '/images/1_black_box_30x30x1_with_red_back.png', $image);
    }

    protected function assertPng($expectedFile, \PatternGif\Image $actualImage)
    {
        $actualFile = str_replace('.png', '_actual.png', $expectedFile);
        $actualImage->saveImage($actualFile);

        $this->assertImage($expectedFile, $actualFile);
    }

    /**
     * sudo apt-get install imagemagick
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertImage($expected, $actual, $message = '')
    {
        $descriptors = array(
            array('pipe', 'r'),
            array('pipe', 'w'),
            array('pipe', 'w'),
        );
        $command = 'compare -metric RMSE ' . escapeshellarg($expected) . ' ' . escapeshellarg($actual) . ' /dev/null';
        $proc = proc_open($command, $descriptors, $pipes);

        $diff = stream_get_contents($pipes[2]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        preg_match('#\((.+)\)#', $diff, $match);
        $threshold = floatval($match[1]);
        $this->assertLessThan(0.05, $threshold, $message);
    }
}
