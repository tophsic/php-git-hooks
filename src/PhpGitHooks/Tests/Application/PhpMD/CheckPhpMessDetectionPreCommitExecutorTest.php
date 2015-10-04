<?php

namespace PhpGitHooks\Tests\Application\PhpMD;

use PhpGitHooks\Application\CodeSniffer\CheckCodeStyleCodeSnifferPreCommitExecutor;
use PhpGitHooks\Application\PhpMD\CheckPhpMessDetectionPreCommitExecutor;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;
use PhpGitHooks\Infrastructure\PhpMD\PhpMDHandler;

/**
 * Class CheckPhpMessDetectionPreCommitExecutorTest.
 */
class CheckPhpMessDetectionPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CheckCodeStyleCodeSnifferPreCommitExecutor */
    private $checkPhpMessDetectionPreCommitExecutor;
    /** @var  InMemoryHookConfig */
    private $preCommitConfig;
    /** @var   Mock */
    private $phpMDHandler;
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

        $this->phpMDHandler = \Mockery::mock(PhpMDHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->once()
            ->mock();

        $this->checkPhpMessDetectionPreCommitExecutor = new CheckPhpMessDetectionPreCommitExecutor(
            $this->phpMDHandler,
            $this->preCommitConfig
        );

        $this->checkPhpMessDetectionPreCommitExecutor->run(
            $this->outputInterface,
            array(),
            'neddle'
        );
    }

    /**
     * @test
     */
    public function isDisabled()
    {
        $this->preCommitConfig->setEnabled(false);

        $this->phpMDHandler = \Mockery::mock(PhpMDHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->never()
            ->mock();

        $this->checkPhpMessDetectionPreCommitExecutor = new CheckPhpMessDetectionPreCommitExecutor(
            $this->phpMDHandler,
            $this->preCommitConfig
        );

        $this->checkPhpMessDetectionPreCommitExecutor->run(
            $this->outputInterface,
            array(),
            'neddle'
        );
    }
}
