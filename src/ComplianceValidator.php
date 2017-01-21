<?php
namespace Pds\Skeleton;

class ComplianceValidator
{
    const STATE_OPTIONAL_NOT_PRESENT = 1;
    const STATE_CORRECT_PRESENT = 2;
    const STATE_RECOMMENDED_NOT_PRESENT = 3;
    const STATE_INCORRECT_PRESENT = 4;

    protected $files = null;

    public function execute($root = null)
    {
        $lines = $this->getFiles($root);
        $results = $this->validate($lines);
        $this->outputResults($results);
        return true;
    }

    public function validate($lines)
    {
        $complianceTests = [
            "Command-line executables" => $this->checkBin($lines),
            "Configuration files" => $this->checkConfig($lines),
            "Documentation files" => $this->checkDocs($lines),
            "Web server files" => $this->checkPublic($lines),
            "Other resource files" => $this->checkResources($lines),
            "PHP source code" => $this->checkSrc($lines),
            "Test code" => $this->checkTests($lines),
            "Log of changes between releases" => $this->checkChangelog($lines),
            "Guidelines for contributors" => $this->checkContributing($lines),
            "Licensing information" => $this->checkLicense($lines),
            "Information about the package itself" => $this->checkReadme($lines),
        ];

        $results = [];
        foreach ($complianceTests as $label => $complianceResult) {
            $state = $complianceResult[0];
            $expected = $complianceResult[1];
            $actual = $complianceResult[2];
            $results[$expected] = [
                'label' => $label,
                'state' => $state,
                'expected' => $expected,
                'actual' => $actual,
            ];
        }
        return $results;
    }

    /**
     * Get list of files and directories previously set, or generate from parent project.
     */
    public function getFiles($root = null)
    {
        $root = $root ?: __DIR__ . '/../../../../';
        $root = realpath($root);

        if ($this->files == null) {
            $files = scandir($root);
            foreach ($files as $i => $file) {
                if (is_dir("$root/$file")) {
                    $files[$i] .= "/";
                }
            }
            $this->files = $files;
        }

        return $this->files;
    }

    public function outputResults($results)
    {
        foreach ($results as $result) {
            $this->outputResultLine($result['label'], $result['state'], $result['expected'], $result['actual']);
        }
    }

    protected function outputResultLine($label, $complianceState, $expected, $actual)
    {
        $messages = [
            self::STATE_OPTIONAL_NOT_PRESENT => "Optional {$expected} not present",
            self::STATE_CORRECT_PRESENT => "Correct {$actual} present",
            self::STATE_INCORRECT_PRESENT => "Incorrect {$actual} present",
            self::STATE_RECOMMENDED_NOT_PRESENT => "Recommended {$expected} not present",
        ];
        echo $this->colorConsoleText("- " . $label . ": " . $messages[$complianceState], $complianceState) . PHP_EOL;
    }

    protected function colorConsoleText($text, $complianceState)
    {
        $colors = [
            self::STATE_OPTIONAL_NOT_PRESENT => "\033[43;30m",
            self::STATE_CORRECT_PRESENT => "\033[42;30m",
            self::STATE_INCORRECT_PRESENT => "\033[41m",
            self::STATE_RECOMMENDED_NOT_PRESENT => "\033[41m",
        ];
        if (!array_key_exists($complianceState, $colors)) {
            return $text;
        }
        return $colors[$complianceState] . " " . $text . " \033[0m";
    }

    protected function checkDir($lines, $pass, array $fail)
    {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == $pass) {
                return [self::STATE_CORRECT_PRESENT, $pass, $line];
            }
            if (in_array($line, $fail)) {
                return [self::STATE_INCORRECT_PRESENT, $pass, $line];
            }
        }
        return [self::STATE_OPTIONAL_NOT_PRESENT, $pass, null];
    }

    protected function checkFile($lines, $pass, array $fail, $state = self::STATE_OPTIONAL_NOT_PRESENT)
    {
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match("/^{$pass}(\.[a-z]+)?$/", $line)) {
                return [self::STATE_CORRECT_PRESENT, $pass, $line];
            }
            foreach ($fail as $regex) {
                if (preg_match($regex, $line)) {
                    return [self::STATE_INCORRECT_PRESENT, $pass, $line];
                }
            }
        }
        return [$state, $pass, null];
    }

    protected function checkChangelog($lines)
    {
        return $this->checkFile($lines, 'CHANGELOG', [
            '/^.*CHANGLOG.*$/i',
            '/^.*CAHNGELOG.*$/i',
            '/^WHATSNEW(\.[a-z]+)?$/i',
            '/^RELEASE((_|-)?NOTES)?(\.[a-z]+)?$/i',
            '/^RELEASES(\.[a-z]+)?$/i',
            '/^CHANGES(\.[a-z]+)?$/i',
            '/^CHANGE(\.[a-z]+)?$/i',
            '/^HISTORY(\.[a-z]+)?$/i',
        ]);
    }

    protected function checkContributing($lines)
    {
        return $this->checkFile($lines, 'CONTRIBUTING', [
            '/^DEVELOPMENT(\.[a-z]+)?$/i',
            '/^README\.CONTRIBUTING(\.[a-z]+)?$/i',
            '/^DEVELOPMENT_README(\.[a-z]+)?$/i',
            '/^CONTRIBUTE(\.[a-z]+)?$/i',
            '/^HACKING(\.[a-z]+)?$/i',
        ]);
    }

    protected function checkLicense($lines)
    {
        return $this->checkFile(
            $lines,
            'LICENSE',
            [
                '/^.*EULA.*$/i',
                '/^.*(GPL|BSD).*$/i',
                '/^([A-Z-]+)?LI(N)?(S|C)(E|A)N(S|C)(E|A)(_[A-Z_]+)?(\.[a-z]+)?$/i',
                '/^COPY(I)?NG(\.[a-z]+)?$/i',
                '/^COPYRIGHT(\.[a-z]+)?$/i',
            ],
            self::STATE_RECOMMENDED_NOT_PRESENT
        );
    }

    protected function checkReadme($lines)
    {
        return $this->checkFile($lines, 'README', [
            '/^USAGE(\.[a-z]+)?$/i',
            '/^SUMMARY(\.[a-z]+)?$/i',
            '/^DESCRIPTION(\.[a-z]+)?$/i',
            '/^IMPORTANT(\.[a-z]+)?$/i',
            '/^NOTICE(\.[a-z]+)?$/i',
            '/^GETTING(_|-)STARTED(\.[a-z]+)?$/i',
        ]);
    }

    protected function checkBin($lines)
    {
        return $this->checkDir($lines, 'bin/', [
            'cli/',
            'scripts/',
            'console/',
            'shell/',
            'script/',
        ]);
    }

    protected function checkConfig($lines)
    {
        return $this->checkDir($lines, 'config/', [
            'etc/',
            'settings/',
            'configuration/',
            'configs/',
            '_config/',
            'conf/',
        ]);
    }

    protected function checkDocs($lines)
    {
        return $this->checkDir($lines, 'docs/', [
            'manual/',
            'documentation/',
            'usage/',
            'doc/',
            'guide/',
            'phpdoc/',
            'apidocs/',
            'apidoc/',
            'api-reference/',
            'user_guide/',
            'manuals/',
            'phpdocs/',
        ]);
    }

    protected function checkPublic($lines)
    {
        return $this->checkDir($lines, 'public/', [
            'assets/',
            'static/',
            'html/',
            'httpdocs/',
            'media/',
            'docroot/',
            'css/',
            'fonts/',
            'styles/',
            'style/',
            'js/',
            'javascript/',
            'images/',
            'site/',
            'mysite/',
            'img/',
            'web/',
            'pub/',
            'webroot/',
            'www/',
            'htdocs/',
            'asset/',
            'public_html/',
            'publish/',
            'pages/',
            'javascripts/',
            'icons/',
            'imgs/',
            'wwwroot/',
            'font/',
        ]);
    }

    protected function checkSrc($lines)
    {
        return $this->checkDir($lines, 'src/', [
            'exception/',
            'exceptions/',
            'src-files/',
            'traits/',
            'interfaces/',
            'common/',
            'sources/',
            'php/',
            'inc/',
            'libraries/',
            'autoloads/',
            'autoload/',
            'source/',
            'includes/',
            'include/',
            'lib/',
            'libs/',
            'library/',
            'code/',
            'classes/',
            'func/',
            'src-dev/',
        ]);
    }

    protected function checkTests($lines)
    {
        return $this->checkDir($lines, 'tests/', [
            'test/',
            'unit-tests/',
            'phpunit/',
            'testing/',
            'unittest/',
            'unit_tests/',
            'unit_test/',
            'phpunit-tests/',
        ]);
    }

    protected function checkResources($lines)
    {
        return $this->checkDir($lines, 'resources/', [
            'Resources/',
            'res/',
            'resource/',
            'Resource/',
            'ressources/',
            'Ressources/',
        ]);
    }
}
