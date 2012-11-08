<?php
namespace Barberry\Plugin\OpenOffice;
use Barberry\Direction;
use Barberry\Direction\Composer;
use Barberry\Monitor;

class InstallerTest extends \PHPUnit_Framework_TestCase
{
    private $directionDir;
    private $monitorDir;
    private $tempDir;

    protected function setUp()
    {
        $this->directionDir = realpath(__DIR__ . '/..' ) . '/tmp/test-directions/';
        $this->monitorDir = realpath(__DIR__ . '/..') . '/tmp/test-monitors/';
        $this->tempDir = realpath(__DIR__ . '/..') . '/tmp/test-temp/';
        @mkdir($this->directionDir, 0777, true);
        @mkdir($this->monitorDir, 0777, true);
        @mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown()
    {
        exec("rm -rf " . $this->directionDir);
        exec("rm -rf " . $this->monitorDir);
        exec("rm -rf " . $this->tempDir);
    }

    public function testInstallsGIFtoJPGDirection()
    {
        $installer = new Installer('/tmp/');
        $installer->install(new Composer($this->directionDir, $this->tempDir),
            new Monitor\Composer($this->monitorDir, $this->tempDir));

        include $this->directionDir . 'OdtToPdf.php';
        $odtToPdf = new Direction\OdtToPdfDirection('');
        //$this->assertNotNull($odtToPdf->convert(file_get_contents(__DIR__ . '/data/1x1.gif')));
    }
}
