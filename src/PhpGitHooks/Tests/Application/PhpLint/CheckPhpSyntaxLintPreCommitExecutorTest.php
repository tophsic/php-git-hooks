<?php

namespace PhpGitHooks\Tests\Application\PhpLint;

use PhpGitHooks\Application\PhpLint\CheckPhpSyntaxLintPreCommitExecutor;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;
use PhpGitHooks\Infrastructure\PhpLint\PhpLintHandler;

/**
 * Class CheckPhpSyntaxLintPreCommitExecutorTest.
 */
class CheckPhpSyntaxLintPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CheckPhpSyntaxLintPreCommitExecutor */
    private $checkPhpSyntaxLintPreCommitExecutor;
    /** @var  Mock */
    private $phpLintHandler;
    /** @var  InMemoryHookConfig */
    private $preCommitConfig;
    /** @var  InMemoryOutputInterface */
    private $outputInterface;

    protected function setUp()
    {
        $this->preCommitConfig = new InMemoryHookConfig();
        $this->outputInterface = new InMemoryOutputInterface();
    }

    /**
     * @test
     */
    public function isEnabled()
    {
        $this->preCommitConfig->setEnabled(true);

        $this->phpLintHandler = \Mockery::mock(PhpLintHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->once()
            ->mock();

        $this->checkPhpSyntaxLintPreCommitExecutor = new CheckPhpSyntaxLintPreCommitExecutor(
            $this->phpLintHandler,
            $this->preCommitConfig
        );

        $this->checkPhpSyntaxLintPreCommitExecutor->run($this->outputInterface, array());
    }

    /**
     * @test
     */
    public function isDisabled()
    {
        $this->preCommitConfig->setEnabled(false);

        $this->phpLintHandler = \Mockery::mock(PhpLintHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->never()
            ->mock();

        $this->checkPhpSyntaxLintPreCommitExecutor = new CheckPhpSyntaxLintPreCommitExecutor(
            $this->phpLintHandler,
            $this->preCommitConfig
        );

        $this->checkPhpSyntaxLintPreCommitExecutor->run($this->outputInterface, array());
    }
}
