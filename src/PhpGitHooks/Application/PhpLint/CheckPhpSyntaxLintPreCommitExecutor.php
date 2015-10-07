<?php

namespace PhpGitHooks\Application\PhpLint;

use PhpGitHooks\Application\Config\HookConfigInterface;
use PhpGitHooks\Infrastructure\Common\FilesToolHandlerInterface;
use PhpGitHooks\Infrastructure\Common\PreCommitExecutor;
use PhpGitHooks\Infrastructure\PhpLint\PhpLintHandler;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckPhpSyntaxLintPreCommitExecutor.
 */
class CheckPhpSyntaxLintPreCommitExecutor extends PreCommitExecutor
{
    /** @var  PhpLintHandler */
    private $phpLintHandler;

    /**
     * @param HookConfigInterface       $hookConfigInterface
     * @param FilesToolHandlerInterface $filesToolHandlerInterface
     */
    public function __construct(
        FilesToolHandlerInterface $filesToolHandlerInterface,
        HookConfigInterface $hookConfigInterface
    ) {
        $this->phpLintHandler = $filesToolHandlerInterface;
        $this->preCommitConfig = $hookConfigInterface;
    }

    /**
     * @param OutputInterface $output
     * @param array           $files
     *
     * @throws string PhpLintException
     */
    public function run(OutputInterface $output, array $files)
    {
        if ($this->isEnabled()) {
            $this->phpLintHandler->setOutput($output);
            $this->phpLintHandler->setFiles($files);
            $this->phpLintHandler->run();
        }
    }

    /**
     * @return string
     */
    protected function commandName()
    {
        return 'phplint';
    }
}
