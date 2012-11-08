<?php
namespace Barberry\Plugin\OpenOffice;
use Barberry\ContentType;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    private $tempDir;

    protected function setUp()
    {
        $this->tempDir = realpath(__DIR__ . '/..') . '/tmp/test-temp/';
        @mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown()
    {
        exec("rm -rf " . $this->tempDir);
    }

    public function testImplementsConverterInterface()
    {
        $converter = new Converter();
        $converter->configure(ContentType::xls(), '');
        $this->assertInstanceOf('Barberry\Plugin\InterfaceConverter', $converter);
    }

    public function testConvertsGitToJpegWithResizing()
    {
        $bin = $this->converter()->convert(file_get_contents(__DIR__ . '/data/test.odt'));
        $this->assertEquals(ContentType::pdf(), ContentType::byString($bin));
    }

    private function converter()
    {
        $converter = new Converter();
        return $converter->configure(ContentType::pdf(), $this->tempDir);
    }


}
