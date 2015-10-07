<?php

namespace PhpGitHooks\Command;

use PhpGitHooks\Application\CodeSniffer\CheckCodeStyleCodeSnifferPreCommitExecutor;
use PhpGitHooks\Application\PhpCsFixer\FixCodeStyleCsFixerPreCommitExecutor;
use PhpGitHooks\Application\PhpMD\CheckPhpMessDetectionPreCommitExecutor;
use PhpGitHooks\Application\PhpUnit\UnitTestPreCommitExecutor;
use PhpGitHooks\Infrastructure\Git\FilesExtractorInterface;
use PhpGitHooks\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QualityCodeTool.
 */
class QualityCodeTool extends Application
{

    /** @var  OutputInterface */
    private $output;
    /** @var  FilesExtractorInterface */
    private $filesExtractor;
    /** @var  FilesInterface */
    private $commitedFiles;
    /** @var  Container */
    private $container;
    /** @var  OutputHandler */
    private $outputTitleHandler;

    const PHP_FILES = '/^(.*)(\.php)$/';
    const JSON_FILES = '/^(.*)(\.json)$/';
    const COMPOSER_FILES = '/^composer\.(json|lock)$/';

    public function __construct()
    {
        $this->outputTitleHandler = new OutputHandler();

        parent::__construct('Code Quality Tool');
    }

    public function setFilesExtractor(FilesExtractorInterface $filesExtractor)
    {
        $this->filesExtractor = $filesExtractor;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (!isset($this->container)) {
            throw new \RuntimeException('Container is not set');
        }

        $this->output = $output;

        $this->output->writeln('<fg=white;options=bold;bg=red>Pre-commit tool</fg=white;options=bold;bg=red>');
        $this->extractCommitFiles();

        $this->execute();

        if (true === $this->existsFiles()) {
            $this->output->writeln(GoodJobLogo::paint());
        }
    }

    private function extractCommitFiles()
    {
        $this->outputTitleHandler->setTitle('Fetching files');
        $this->output->write($this->outputTitleHandler->getTitle());

        $this->commitedFiles = $this->filesExtractor->extract();

        $result = true === $this->existsFiles() ? '0k' : 'No files changed';
        $this->output->writeln($this->outputTitleHandler->getSuccessfulStepMessage($result));

    }

    /**
     * @return array
     */
    private function processingFiles()
    {
        $files = [
            'php' => false,
            'composer' => false,
            'json' => false
        ];

        foreach ($this->commitedFiles->getFiles() as $file) {
            if (true === (bool)preg_match(self::PHP_FILES, $file)) {
                $files['php'] = true;
            }

            if (true === (bool)preg_match(self::COMPOSER_FILES, $file)) {
                $files['composer'] = true;
            }

            if (true === (bool)preg_match(self::JSON_FILES, $file)) {
                $files['json'] = true;
            }
        }

        return $files;
    }

    private function execute()
    {
        if (true === $this->isProcessingAnyComposerFile()) {
            $this->container->get('check.composer.files.pre.commit.executor')
                ->run($this->output, $this->commitedFiles->getFiles());
        }

        if (true === $this->isProcessingAnyJsonFile()) {
            $this->container->get('check.json.syntax.pre.commit.executor')
                ->run($this->output, $this->commitedFiles, self::JSON_FILES);
        }

        if (true === $this->isProcessingAnyPhpFile()) {
            $this->container->get('check.php.syntax.lint.pre.commit.executor')
                ->run($this->output, $this->commitedFiles->getFiles());

            /** @var FixCodeStyleCsFixerPreCommitExecutor $csFixer */
            $csFixer = $this->container->get('fix.code.style.cs.fixer.pre.commit.executor');
            $csFixer->run($this->output, $this->commitedFiles->getFiles(), self::PHP_FILES);

            /** @var CheckCodeStyleCodeSnifferPreCommitExecutor $codeSniffer */
            $codeSniffer = $this->container->get('check.code.style.code.sniffer.pre.commit.executor');
            $codeSniffer->run($this->output, $this->commitedFiles, self::PHP_FILES);

            /** @var CheckPhpMessDetectionPreCommitExecutor $messDetector */

            $messDetector = $this->container->get('check.php.mess.detection.pre.commit.executor');
            $messDetector->run($this->output, $this->commitedFiles->getFiles(), self::PHP_FILES);

            /** @var UnitTestPreCommitExecutor $phpUnit */
            $phpUnit = $this->container->get('unit.test.pre.commit.executor');
            $phpUnit->run($this->output);
        }
    }

    /**
     * @return bool
     */
    private function isProcessingAnyComposerFile()
    {
        $files = $this->processingFiles();

        return $files['composer'];
    }

    /**
     * @return bool
     */
    private function isProcessingAnyPhpFile()
    {
        $files = $this->processingFiles();

        return $files['php'];
    }

    /**
     * @return bool
     */
    private function isProcessingAnyJsonFile()
    {
        $files = $this->processingFiles();

        return $files['json'];
    }

    /**
     * @return bool
     */
    private function existsFiles()
    {
        return count($this->commitedFiles->getFiles()) > 1 ? true : false;
    }
}
