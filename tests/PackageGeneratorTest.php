<?php
namespace Pds\Skeleton;

class PackageGeneratorTest
{
    public $numErrors = 0;

    public static function run()
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        });

        $tester = new PackageGeneratorTest();
        $tester->testGenerate_WithMissingBin_ReturnsBin();
        $tester->testGenerate_WithExistingDir_SkipsDir();
        $tester->testGenerate_WithExistingFile_SkipsFile();
        $tester->testGenerate_WithBadPermissions_SkipsDirAndThrowsException();
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

        $files = (new PackageGenerator())->createFileList($validatorResults);

        if (!array_key_exists('bin/', $files)) {
            $this->numErrors++;
                echo __FUNCTION__ . ": Expected bin/ to be present" . PHP_EOL;
        }

        if (array_key_exists('config/', $files)) {
            $this->numErrors++;
                echo __FUNCTION__ . ": Expected config/ to be absent" . PHP_EOL;
        }
    }

    public function testGenerate_WithExistingDir_SkipsDir()
    {
        $validatorResults = [
            'bin/' => [
                'state' => ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT,
                'expected' => 'bin/',
            ],
        ];

        $tmp = __DIR__.'/tmp_allowed';
        $bin = $tmp.'/bin';
        if (!is_dir($tmp)) {
            mkdir($tmp, 0755);
        }
        if (!is_dir($bin)) {
            mkdir($bin, 0755);
        }

        try {
            $createdFiles = (new PackageGenerator())->createFiles($validatorResults, $tmp);
        } catch (GeneratorException $e) {
            $this->numErrors++;
            echo __FUNCTION__ . ": Unexpected exception" . PHP_EOL;
        }

        if (!array_key_exists('bin/', $createdFiles)) {
            $this->numErrors++;
            echo __FUNCTION__ . ": Expected to return bin/" . PHP_EOL;
        }

        rmdir($tmp.'/bin');
        rmdir($tmp);
    }

    public function testGenerate_WithExistingFile_SkipsFile()
    {
        $validatorResults = [
            'LICENSE' => [
                'state' => ComplianceValidator::STATE_RECOMMENDED_NOT_PRESENT,
                'expected' => 'LICENSE',
            ],
        ];

        $tmp = __DIR__.'/tmp_allowed';
        $file = $tmp.'/LICENSE.md';
        if (!is_dir($tmp)) {
            mkdir($tmp, 0755);
        }
        touch($file);
        file_put_contents($file, 'Original content');

        try {
            $createdFiles = (new PackageGenerator())->createFiles($validatorResults, $tmp);
        } catch (GeneratorException $e) {
            $this->numErrors++;
            echo __FUNCTION__ . ': Unexpected exception' . PHP_EOL;
        }

        if (!array_key_exists('LICENSE', $createdFiles)) {
            $this->numErrors++;
            echo __FUNCTION__ . ': Expected to return file' . PHP_EOL;
        }

        if (file_get_contents($file) !== 'Original content') {
            $this->numErrors++;
            echo __FUNCTION__ . ': Expected to preserve original content' . PHP_EOL;
        }

        unlink($file);
        rmdir($tmp);
    }

    public function testGenerate_WithBadPermissions_SkipsDirAndThrowsException()
    {
        $validatorResults = [
            'bin/' => [
                'state' => ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT,
                'expected' => 'bin/',
            ],
        ];

        $tmp = __DIR__.'/tmp_forbidden';
        if (!is_dir($tmp)) {
            mkdir($tmp, 0644);
        }

        try {
            (new PackageGenerator())->createFiles($validatorResults, $tmp);
        } catch (GeneratorException $e) {
            rmdir($tmp);
            return;
        }

        $this->numErrors++;
        echo __FUNCTION__ . ": Expected exception, but none was thrown" . PHP_EOL;

        if (!is_dir($tmp.'/bin')) {
            $this->numErrors++;
            echo __FUNCTION__ . ": Expected to not create a directory, but bin/ was created" . PHP_EOL;
        }

        rmdir($tmp);
    }
}