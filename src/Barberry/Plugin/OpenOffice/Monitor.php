<?php
namespace Barberry\Plugin\Openoffice;
use Barberry\Plugin;
use Barberry\ContentType;

class Monitor implements Plugin\InterfaceMonitor
{
    private $tempDirectory;

    private $dependencies = array(
        'python' => 'Please install python'
    );

    /**
     * @param string $tempDirectory
     * @return self
     */
    public function configure($tempDirectory) {
        $this->tempDirectory = $tempDirectory;
        return $this;
    }

    public function dependencies()
    {
        return $this->dependencies;
    }

    public function reportUnmetDependencies()
    {
        $errors = array();
        foreach ($this->dependencies() as $command => $message) {
            $answer = $this->reportUnixCommand($command, $message);
            if (!is_null($answer)) {
                $errors[] = $answer;
            }
        }
        return $errors;
    }

    public function reportMalfunction()
    {
        $answer = $this->reportWritableDirectory($this->tempDirectory);
        if ($answer === null) {
            $answer = $this->reportOpenOfficeConverter();
        }

        return (!is_null($answer)) ? array($answer) : array();
    }

//-------------------------------------------------------------------------

    private function reportOpenOfficeConverter() {
        $converter = new Converter();
        $converter->configure(ContentType::pdf(), $this->tempDirectory);

        try {
            $bin = $converter->convert(file_get_contents(__DIR__ . '/../../../../test/data/test.odt'));
        } catch (OpenOfficeException $e) {
            return 'ERROR: Plugin openoffice cannot convert document.';
        }
        return strval(ContentType::pdf()) === strval(ContentType::byString($bin)) ?
                null : 'ERROR: Plugin openoffice converts to wrong content type.';
    }

    private function reportWritableDirectory($directory)
    {
        return (!is_writeable($directory)) ? 'ERROR: Plugin openoffice temporary directory is not writeable.' : null;
    }

    private function reportUnixCommand($command, $messageIfMissing)
    {
        return preg_match('/^\/\w+/', exec("which $command 2>/dev/null")) ? null : "MISSING - $messageIfMissing\n\n";
    }

}
