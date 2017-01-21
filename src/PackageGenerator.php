<?php
namespace Pds\Skeleton;

class PackageGenerator
{
    public function execute($root = null)
    {
        $validator = new ComplianceValidator();
        $lines = $validator->getFiles();
        $validatorResults = $validator->validate($lines);
        $files = $this->createFiles($validatorResults, $root);
        $this->outputResults($files);
        return true;
    }

    public function createFiles($validatorResults, $root = null)
    {
        $root = $root ?: __DIR__ . '/../../../../';
        $root = realpath($root);

        $files = $this->createFileList($validatorResults);
        $createdFiles = [];

        foreach ($files as $i => $file) {
            $isDir = substr($file, -1, 1) == '/';
            if ($isDir) {
                $path = $root . '/' . substr($file, 0, -1);
                $createdFiles[$file] = $path;
                mkdir($path, 0755);
                continue;
            }
            $path = $root . '/' . $file . '.md';
            $createdFiles[$file] = $file . '.md';
            file_put_contents($path, '');
            chmod($path, 0644);
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
