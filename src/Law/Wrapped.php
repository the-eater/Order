<?php

namespace Eater\Order\Law;

class Wrapped {

    private $lawFile;

    public function __construct($lawFile)
    {
        $this->lawFile = $lawFile;  
    }

    public function execute()
    {
        if (!\is_file($this->lawFile)) {
            throw new FileNotFound($this->lawFile);
        }

        \exec('php -l ' . escapeshellarg($this->lawFile), $output, $returnCode);

        if ($returnCode !== 0) {
            throw new InvalidSyntax($output, $this->lawFile);
        }

        WrappedExecutor::clearDefinitions();
        WrappedExecutor::execute($this->lawFile);
        return WrappedExecutor::getDefinitions();
    }
}
