<?php
namespace Pds\Skeleton;

class ComplianceValidatorTest
{
    public $numErrors = 0;

    public static function run()
    {
        $tester = new ComplianceValidatorTest();
        $tester->testValidate_WithIncorrectBin_ReturnsIncorrectBin();
        $tester->testValidate_WithoutVendor_ReturnsMissingVendor();
        echo __CLASS__ . " errors: {$tester->numErrors}" . PHP_EOL;
    }

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