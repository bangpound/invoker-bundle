<?php

namespace Bangpound\Bundle\InvokerBundle\Command;

use Bangpound\Bundle\InvokerBundle\Process\ProcessBuilder;
use Psr\Log\LogLevel;
use React\EventLoop\Factory;
use React\EventLoop\Timer\Timer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class InvokerCommand extends ContainerAwareCommand
{
    /**
     * @var ProcessBuilder[]
     */
    protected $builders;

    protected function configure()
    {
        $this
            ->setName('invoker');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $this->getContainer()->getParameter('kernel.environment');

        if ('prod' === $env) {
            $output->writeln('<error>Running PHP built-in server in production environment is NOT recommended!</error>');
        }

        $loop = Factory::create();
        $logger = $this->getContainer()->get('logger');

        foreach ($this->builders as $builder) {
            $process = $builder->getProcess();

            $process->on('exit', function ($exitCode, $termSignal) use ($logger) {
                $logger->log(LogLevel::INFO, $exitCode);
                $logger->log(LogLevel::INFO, $termSignal);
            });

            $loop->addTimer(Timer::MIN_INTERVAL, function (Timer $timer) use ($process, $logger) {
                $process->start($timer->getLoop());

                $process->stdout->on('data', function ($data) use ($logger) {
                    $logger->log(LogLevel::INFO, $data);
                });
            });
        }

        $loop->run();
    }

    public function addBuilder(ProcessBuilder $builder)
    {
        $this->builders[] = $builder;
    }
}
