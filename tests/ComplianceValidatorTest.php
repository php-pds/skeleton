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

$tester = new ComplianceValidatorTest();
// Test all 4 possible states.
$tester->testValidate_WithIncorrectBin_ReturnsIncorrectBin();
$tester->testValidate_WithoutVendor_ReturnsMissingVendor();

echo "Errors: {$tester->numErrors}" . PHP_EOL;

class ComplianceValidatorTest
{
    public $numErrors = 0;

    public function testValidate_WithIncorrectBin_ReturnsIncorrectBin()
    {
        $paths = [
            'cli/',
            'vendor/',
        ];

        $validator = new ComplianceValidator();
        $results = $validator->validate($paths);

        foreach ($results as $expected => $result) {
            if ($expected == "bin/") {
                if ($result['state'] != ComplianceValidator::STATE_INCORRECT_PRESENT) {
                    $this->numErrors++;
                    echo __FUNCTION__ . ": Expected state of {$result['expected']} to be STATE_INCORRECT_PRESENT" . PHP_EOL;
                }
                continue;
            }
            if ($expected == "vendor/") {
                if ($result['state'] != ComplianceValidator::STATE_CORRECT_PRESENT) {
                    $this->numErrors++;
                    echo __FUNCTION__ . ": Expected state of {$result['expected']} to be STATE_CORRECT_PRESENT" . PHP_EOL;
                }
                continue;
            }
            if ($result['state'] != ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT) {
                $this->numErrors++;
                echo __FUNCTION__ . ": Expected state of {$result['expected']} to be STATE_OPTIONAL_NOT_PRESENT" . PHP_EOL;
                continue;
            }
        }
    }

    public function testValidate_WithoutVendor_ReturnsMissingVendor()
    {
        $paths = [
            'bin/',
        ];

        $validator = new ComplianceValidator();
        $results = $validator->validate($paths);

        foreach ($results as $expected => $result) {
            if ($expected == "vendor/") {
                if ($result['state'] != ComplianceValidator::STATE_REQUIRED_NOT_PRESENT) {
                    $this->numErrors++;
                    echo __FUNCTION__ . ": Expected state of {$result['expected']} to be STATE_REQUIRED_NOT_PRESENT" . PHP_EOL;
                }
                continue;
            }
        }
    }
}