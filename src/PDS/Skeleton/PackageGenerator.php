<?php

namespace PDS\Skeleton;

use PDS\Skeleton\ComplianceValidator;

class PackageGenerator
{
    public function execute()
    {
        $validator = new ComplianceValidator();
        $lines = $validator->getFiles();
        $validatorResults = $validator->validate($lines);
        $files = $this->createFiles($validatorResults);
        $this->outputResults($files);
        return true;
    }

    public function createFiles($validatorResults, $root = null)
    {
        if ($root == null) {
            $root = realpath(__DIR__ . "/../../../../../../");
        }
        $files = $this->createFileList($validatorResults);
        foreach ($files as $file) {
            $isDir = substr($file, -1, 1) == '/';
            if ($isDir) {
                $path = $root . '/' . substr($file, 0, -1);
                mkdir($path, 0755);
                continue;
            }
            $path = $root . '/' . $file . '.md';
            file_put_contents($path, '');
            chmod($path, 0644);
        }
        return $files;
    }

    public function createFileList($validatorResults)
    {
        $files = [];
        foreach ($validatorResults as $label => $complianceResult) {
            if (in_array($complianceResult['state'], [
                ComplianceValidator::STATE_OPTIONAL_NOT_PRESENT,
                ComplianceValidator::STATE_REQUIRED_NOT_PRESENT,
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