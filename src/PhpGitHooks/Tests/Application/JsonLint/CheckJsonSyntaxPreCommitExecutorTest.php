<?php

namespace PhpGitHooks\Tests\Application\JsonLint;

use PhpGitHooks\Application\JsonLint\CheckJsonSyntaxPreCommitExecutor;
use PhpGitHooks\Infrastructure\Common\RecursiveToolInterface;
use PhpGitHooks\Infrastructure\Component\InMemoryOutputInterface;
use PhpGitHooks\Infrastructure\Config\InMemoryHookConfig;
use PhpGitHooks\Infrastructure\JsonLint\JsonLintHandler;
use Symfony\Component\Console\Output\OutputInterface;

class CheckJsonSyntaxPreCommitExecutorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CheckJsonSyntaxPreCommitExecutor */
    private $checkJsonSyntaxPreCommitExecutor;
    /** @var  Mock */
    private $jsonLintHandler;
    /** @var  InMemoryHookConfig */
    private $hookConfig;
    /** @var  OutputInterface */
    public $outputInterface;

    protected function setUp()
    {
        $this->hookConfig = new InMemoryHookConfig();
        $this->outputInterface = new InMemoryOutputInterface();
    }

    /**
     * @test
     */
    public function isEnabled()
    {
        $this->hookConfig->setEnabled(true);

        $this->jsonLintHandler = \Mockery::mock(JsonLintHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->once()
            ->mock();

        $this->checkJsonSyntaxPreCommitExecutor = new CheckJsonSyntaxPreCommitExecutor(
            $this->jsonLintHandler,
            $this->hookConfig
        );

        $this->checkJsonSyntaxPreCommitExecutor->run($this->outputInterface, [], 'needle');
    }

    /**
     * @test
     */
    public function isDisabled()
    {
        $this->hookConfig->setEnabled(false);

        $this->jsonLintHandler = \Mockery::mock(JsonLintHandler::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('run')
            ->never()
            ->mock();

        $this->checkJsonSyntaxPreCommitExecutor = new CheckJsonSyntaxPreCommitExecutor(
            $this->jsonLintHandler,
            $this->hookConfig
        );

        $this->checkJsonSyntaxPreCommitExecutor->run($this->outputInterface, [], 'needle');
    }
}
