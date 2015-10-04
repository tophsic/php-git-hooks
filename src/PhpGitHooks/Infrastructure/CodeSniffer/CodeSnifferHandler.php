<?php

namespace PhpGitHooks\Infrastructure\CodeSniffer;

use PhpGitHooks\Command\BadJobLogo;
use PhpGitHooks\Infrastructure\Common\ToolHandler;
use PhpGitHooks\Infrastructure\Git\FilesInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class CodeSnifferHandler.
 */
class CodeSnifferHandler extends ToolHandler
{
    /** @var array */
    private $files;
    /** @var string */
    private $needle;
    /** @var string */
    private $standard = 'PSR2';

    /**
     * @throws InvalidCodingStandardException
     */
    public function run()
    {
        $this->outputHandler->setTitle('Checking code style with PHPCS');
        $this->output->write($this->outputHandler->getTitle());

        if (!isset($this->files)) {
            return;
        }

        foreach ($this->files->getFiles() as $file) {
            if (!preg_match($this->needle, $file)) {
                continue;
            }

            $processBuilder = new ProcessBuilder(array(
                'php', 'bin/phpcs', '--standard='.$this->standard, $file
            ));
            /** @var Process $phpCs */
            $phpCs = $processBuilder->getProcess();
            $phpCs->run();

            if (false === $phpCs->isSuccessful()) {
                $this->outputHandler->setError($phpCs->getOutput());
                $this->output->writeln($this->outputHandler->getError());
                $this->output->writeln(BadJobLogo::paint());

                throw new InvalidCodingStandardException();
            }
        }

        $this->output->writeln($this->outputHandler->getSuccessfulStepMessage());
    }

    /**
     * @param string $needle
     */
    public function setNeedle($needle)
    {
        $this->needle = $needle;
    }

    /**
     * @param FilesInterface $files
     */
    public function setFiles(FilesInterface $files)
    {
        $this->files = $files;
    }

    /**
     * @param array $standard
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
    }
}
