<?php

namespace PhpGitHooks\Tests\Application\Composer;

use PhpGitHooks\Application\Composer\CheckComposerFilesPreCommitExecutor;
use PhpGitHooks\Application\Composer\ComposerFilesValidator;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;

class CheckComposerFilesPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Mock */
    private $composerFilesValidator;
    /** @var  CheckComposerFilesPreCommitExecutor */
    private $checkComposerFilesPreCommitExecutor;
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
    public function runSuccessful()
    {
        $this->preCommitConfig->setEnabled(true);

        $this->composerFilesValidator = \Mockery::mock(ComposerFilesValidator::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('validate')
            ->once()
            ->mock();

        $this->checkComposerFilesPreCommitExecutor = new CheckComposerFilesPreCommitExecutor(
            $this->composerFilesValidator,
            $this->preCommitConfig
        );

        $this->checkComposerFilesPreCommitExecutor->run($this->outputInterface, array());
    }

    /**
     * @test
     */
    public function toolIsDisabled()
    {
        $this->preCommitConfig->setEnabled(false);

        $this->composerFilesValidator = \Mockery::mock(ComposerFilesValidator::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('validate')
            ->never()
            ->mock();

        $this->checkComposerFilesPreCommitExecutor = new CheckComposerFilesPreCommitExecutor(
            $this->composerFilesValidator,
            $this->preCommitConfig
        );

        $this->checkComposerFilesPreCommitExecutor->run($this->outputInterface, array());
    }
}
