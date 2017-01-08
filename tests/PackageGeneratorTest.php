<?php
namespace Pds\Skeleton;

class PackageGeneratorTest
{
    public $numErrors = 0;

    public static function run()
    {
        $tester = new PackageGeneratorTest();
        $tester->testGenerate_WithMissingBin_ReturnsBin();
        echo __CLASS__ . " errors: {$tester->numErrors}" . PHP_EOL;
    }

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