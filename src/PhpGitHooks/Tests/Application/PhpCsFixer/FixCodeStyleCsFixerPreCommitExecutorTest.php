<?php

namespace PhpGitHooks\Tests\Application\PhpCsFixer;

use PhpGitHooks\Application\PhpCsFixer\FixCodeStyleCsFixerPreCommitExecutor;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;
use PhpGitHooks\Infrastructure\PhpCsFixer\PhpCsFixerHandler;

/**
 * Class FixCodeStyleCsFixerPreCommitExecutorTest.
 */
class FixCodeStyleCsFixerPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  FixCodeStyleCsFixerPreCommitExecutor */
    private $fixCodeStyleCsFixerPreCommitExecutor;
    /** @var  InMemoryHookConfig */
    private $preCommitConfig;
    /** @var  InMemoryOutputInterface */
    private $outputInterface;
    /** @var  Mock */
    private $phpCsFixerHandler;

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
        $this->preCommitConfig->setExtraOptions(['enabled' => true, 'levels' => ['psr0' => true]]);

        $this->phpCsFixerHandler = \Mockery::mock(PhpCsFixerHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->once()
            ->mock();

        $this->fixCodeStyleCsFixerPreCommitExecutor = new FixCodeStyleCsFixerPreCommitExecutor(
            $this->phpCsFixerHandler,
            $this->preCommitConfig
        );

        $this->fixCodeStyleCsFixerPreCommitExecutor->run(
            $this->outputInterface,
            array(),
            'neddle'
        );
    }

    /**
     * @test
     */
    public function idDisabled()
    {
        $this->preCommitConfig->setExtraOptions(['enabled' => false, 'levels' => []]);

        $this->phpCsFixerHandler = \Mockery::mock(PhpCsFixerHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->never()
            ->mock();

        $this->fixCodeStyleCsFixerPreCommitExecutor = new FixCodeStyleCsFixerPreCommitExecutor(
            $this->phpCsFixerHandler,
            $this->preCommitConfig
        );

        $this->fixCodeStyleCsFixerPreCommitExecutor->run(
            $this->outputInterface,
            array(),
            'neddle'
        );
    }
}
