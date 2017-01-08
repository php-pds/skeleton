<?php
namespace Pds\Skeleton;

class TestRunner
{
    public function execute()
    {
        ComplianceValidatorTest::run();
        PackageGeneratorTest::run();
    }
}
