<?php

class LetterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider dataProviderRussianAlphabet
     */
    public function shouldGenerateRussianAlphabet($letter)
    {
        $letterGenerator = new \PatternGif\Letter();
        $image = $letterGenerator->generate($letter);

        $this->assertPng(__DIR__ . '/letters/russian_' . $letter . '.png', $image);
    }

    public function dataProviderRussianAlphabet()
    {
        return [
            ['А'], ['Б'], ['В'], ['Г'], ['Д'],
            ['Е'], ['Ё'], ['Ж'], ['З'], ['И'],
            ['Й'], ['К'], ['Л'], ['М'], ['Н'],
            ['О'], ['П'], ['Р'], ['С'], ['Т'],
            ['У'], ['Ф'], ['Х'], ['Ц'], ['Ч'],
            ['Ш'], ['Щ'], ['Ъ'], ['Ы'], ['Ь'],
            ['Э'], ['Я'],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderEnglishAlphabet
     */
    public function shouldGenerateEnglishAlphabet($letter)
    {
        $letterGenerator = new \PatternGif\Letter();
        $image = $letterGenerator->generate($letter);

        $this->assertPng(__DIR__ . '/letters/english_' . $letter . '.png', $image);
    }

    public function dataProviderEnglishAlphabet()
    {
        return [
            ['A'], ['B'], ['C'], ['D'], ['E'],
            ['F'], ['G'], ['H'], ['I'], ['J'],
            ['K'], ['L'], ['M'], ['N'], ['O'],
            ['P'], ['Q'], ['R'], ['S'], ['T'],
            ['U'], ['V'], ['W'], ['X'], ['Y'],
            ['Z'],
        ];
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

        $this->assertArrayHasKey(1, $match, 'Compare error');
        $this->assertLessThan(0.05, floatval($match[1]), $message);
    }
}
