<?php

namespace PDS\Skeleton;

class Console
{
    protected $commandsWhitelist = [
        'validate' => 'PDS\Skeleton\ComplianceValidator',
    ];

    public function execute($args)
    {
        if (count($args) > 1) {

            $executable = array_shift($args);
            $commandName = array_shift($args);

            if (array_key_exists($commandName, $this->commandsWhitelist)) {
                return $this->executeCommand($this->commandsWhitelist[$commandName], $args);
            }
            
            $this->outputHelp();
            return false;
        }

        $this->outputHelp();
        return false;
    }

    protected function executeCommand($commandClass, $args)
    {
        $command = new $commandClass();
        return $command->execute($args);
    }

    protected function outputHelp()
    {
        echo 'Available commands:' . PHP_EOL;
        foreach ($this->commandsWhitelist as $key => $value) {
            echo 'pdsskeleton ' . $key . PHP_EOL;
        }
    }
}