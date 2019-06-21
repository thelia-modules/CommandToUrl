<?php

namespace CommandToUrl\Service;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Thelia\Core\Application;

class CommandService
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getAllCommands()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'list',
            '--format' => 'json'
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = json_decode($output->fetch());

        return $content->commands;
    }
}