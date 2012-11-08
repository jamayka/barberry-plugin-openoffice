<?php
namespace Barberry\Plugin\Openoffice;

class OpenOfficeException extends \Exception
{

    public function __construct($message) {
        parent::__construct('OpenOffice error: ' . $message, 500);
    }

}
