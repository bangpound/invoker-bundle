<?php

namespace Bangpound\Bundle\InvokerBundle\Process;

use React\ChildProcess\Process;
use Symfony\Component\Process\ProcessBuilder as BaseProcessBuilder;

class ProcessBuilder extends BaseProcessBuilder
{

    /**
     * Creates a Process instance and returns it.
     *
     * @return Process
     */
    public function getProcess()
    {
        $process = parent::getProcess();

        return new Process($process->getCommandLine(), $process->getWorkingDirectory(), $process->getEnv(), $process->getOptions());
    }
}
