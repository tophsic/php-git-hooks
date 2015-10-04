<?php

namespace PhpGitHooks\Tests\Application\CodeSniffer;

use Mockery\Mock;
use PhpGitHooks\Application\CodeSniffer\CheckCodeStyleCodeSnifferPreCommitExecutor;
use PhpGitHooks\Infrastructure\CodeSniffer\CodeSnifferHandler;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;

/**
 * Class CheckCodeStyleCodeSnifferPreCommitExecutorTest.
 */
class CheckCodeStyleCodeSnifferPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CheckCodeStyleCodeSnifferPreCommitExecutor */
    private $checkCodeStyleCodeSnifferPreCommitExecutor;
    /** @var  InMemoryHookConfig */
    private $preCommitConfig;
    /** @var  Mock */
    private $codeSnifferHandler;
    /** @var InMemoryOutputInterface */
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
        $this->preCommitConfig->setExtraOptions(['enabled' => true, 'standard' => 'PSR2' ]);

        $this->codeSnifferHandler = \Mockery::mock(CodeSnifferHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->once()
            ->mock();

        $this->checkCodeStyleCodeSnifferPreCommitExecutor  = new CheckCodeStyleCodeSnifferPreCommitExecutor(
            $this->codeSnifferHandler,
            $this->preCommitConfig
        );

        $this->checkCodeStyleCodeSnifferPreCommitExecutor->run(
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
        $this->preCommitConfig->setExtraOptions(['enabled' => false, 'standard' => '']);

        $this->codeSnifferHandler = \Mockery::mock(CodeSnifferHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->never()
            ->mock();

        $this->checkCodeStyleCodeSnifferPreCommitExecutor  = new CheckCodeStyleCodeSnifferPreCommitExecutor(
            $this->codeSnifferHandler,
            $this->preCommitConfig
        );

        $this->checkCodeStyleCodeSnifferPreCommitExecutor->run(
            $this->outputInterface,
            array(),
            'neddle'
        );
    }
}
