<?php
namespace Pds\Skeleton;

class PackageGenerator
{
    public function execute($root = null)
    {
        $validator = new ComplianceValidator();
        try {
            $lines = $validator->getFiles($root);
            $validatorResults = $validator->validate($lines);
            $files = $this->createFiles($validatorResults, $root);
            $this->outputResults($files);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }

    public function createFiles($validatorResults, $root = null)
    {
        $root = $root ?: __DIR__ . '/../../../../';
        $root = realpath($root);

        if (!is_dir($root)) {
            throw new GeneratorException("Specified directory does not exist. Create it first.");
        }

        if (!is_writable($root) || !is_executable($root)) {
            throw new GeneratorException("Cannot write into specified directory. Please check folder permissions for {$root}");
        }

        $files = $this->createFileList($validatorResults);
        $createdFiles = [];

        foreach ($files as $i => $file) {
            $isDir = substr($file, -1, 1) == '/';
            if ($isDir) {
                $path = $root . '/' . substr($file, 0, -1);
                $createdFiles[$file] = $path;
                !is_dir($path) && !mkdir($path, 0755) && !is_dir($path);
                continue;
            }
            $path = $root . '/' . $file . '.md';
            $createdFiles[$file] = $path;
            if (!file_exists($path)) {
                file_put_contents($path, '');
                chmod($path, 0644);
            }
        }

        return $createdFiles;
    }

    public function createFileList($validatorResults)
    {
        $files = [];
        foreach ($validatorResults as $label => $complianceResult) {
            if (in_array($complianceResult['state'], [
                ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT,
                ComplianceValidator::STATE_RECOMMENDED_NOT_PRESENT,
                ])) {
                $files[$label] = $complianceResult['expected'];
            }
        }
        return $files;
    }

    public function outputResults($results)
    {
        foreach ($results as $file) {
            echo "Created {$file}" . PHP_EOL;
        }
    }
}
