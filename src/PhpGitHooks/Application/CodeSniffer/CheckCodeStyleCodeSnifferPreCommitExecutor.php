<?php

namespace PhpGitHooks\Application\CodeSniffer;

use PhpGitHooks\Application\Config\HookConfigInterface;
use PhpGitHooks\Infrastructure\CodeSniffer\CodeSnifferHandler;
use PhpGitHooks\Infrastructure\CodeSniffer\InvalidCodingStandardException;
use PhpGitHooks\Infrastructure\Common\PreCommitExecutor;
use PhpGitHooks\Infrastructure\Git\FilesInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckCodeStyleCodeSnifferPreCommitExecutor.
 */
class CheckCodeStyleCodeSnifferPreCommitExecutor extends PreCommitExecutor
{
    /** @var  CodeSnifferHandler */
    private $codeSnifferHandler;

    /**
     * @param HookConfigInterface $preCommitConfig
     * @param CodeSnifferHandler  $codeSnifferHandler
     */
    public function __construct(
        CodeSnifferHandler $codeSnifferHandler,
        HookConfigInterface $preCommitConfig
    )
    {
        $this->codeSnifferHandler = $codeSnifferHandler;
        $this->preCommitConfig = $preCommitConfig;
    }

    /**
     * @param OutputInterface       $output
     * @param ExtractCommitedFiles  $files
     * @param string                $needle
     *
     * @throws InvalidCodingStandardException
     */
    public function run(OutputInterface $output, FilesInterface $files, $needle)
    {
        $data = $this->preCommitConfig->extraOptions($this->commandName());

        if (true === $data['enabled']) {
            $this->codeSnifferHandler->setOutput($output);
            $this->codeSnifferHandler->setFiles($files);
            $this->codeSnifferHandler->setNeedle($needle);
            $this->codeSnifferHandler->setStandard($data['standard']);
            $this->codeSnifferHandler->run();
        }
    }

    /**
     * @return string
     */
    protected function commandName()
    {
        return 'phpcs';
    }
}
