<?php

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
        break;
    }
}

use PDS\Skeleton\ComplianceValidator;
use PDS\Skeleton\PackageGenerator;

$tester = new PackageGeneratorTest();
$tester->testGenerate_WithMissingBin_ReturnsBin();

echo "Errors: {$tester->numErrors}" . PHP_EOL;

class PackageGeneratorTest
{
    public $numErrors = 0;

    public function testGenerate_WithMissingBin_ReturnsBin()
    {
        $validatorResults = [
            'bin/' => [
                'state' => ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT,
                'expected' => 'bin/',
            ],
            'config/' => [
                'state' => ComplianceValidator::STATE_INCORRECT_PRESENT,
                'expected' => 'config/',
            ],
        ];

        $generator = new PackageGenerator();
        $files = $generator->createFileList($validatorResults);

        if (!array_key_exists('bin/', $files)) {
            $this->numErrors++;
                echo __FUNCTION__ . ": Expected bin/ to be present" . PHP_EOL;
        }

        if (array_key_exists('config/', $files)) {
            $this->numErrors++;
                echo __FUNCTION__ . ": Expected config/ to be absent" . PHP_EOL;
        }
    }
}