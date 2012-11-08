<?php
namespace Barberry\Plugin\Openoffice;
use Barberry\Plugin;
use Barberry\ContentType;

class Converter implements Plugin\InterfaceConverter
{

    /**
     * @var string
     */
    private $tempPath;

    /**
     * @var ContentType
     */
    private $targetContentType;

    /**
     * @param ContentType $targetContentType
     * @param string $tempPath
     * @return self
     */
    public function configure(ContentType $targetContentType, $tempPath)
    {
        $this->tempPath = $tempPath;
        $this->targetContentType = $targetContentType;
        return $this;
    }

    public function convert($bin, Plugin\InterfaceCommand $command = null)
    {
        $source = tempnam($this->tempPath, "ooconverter_");
        chmod($source, 0664);
        $destination = $source . '.' . $this->targetContentType->standartExtention();
        file_put_contents($source, $bin);
        $out = exec(
            'python ' . __DIR__ . '/../../../../externals/pyodconverter/DocumentConverter.py ' . "$source $destination"
        );
        unlink($source);

        if (!is_file($destination)) {
            throw new OpenOfficeException($out);
        }

        $bin = file_get_contents($destination);
        unlink($destination);
        return $bin;
    }

}
