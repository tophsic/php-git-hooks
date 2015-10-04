<?php

namespace PhpGitHooks\Infrastructure\Git;

/**
 * Class ExtractCommitedFiles.
 */
class ExtractCommitedFiles implements FilesExtractorInterface
{
    /** @var array */
    private $output = array();
    /** @var array */
    private $files = array();
    /** @var string */
    private $gitRef = null;
    /** @var int */
    private $rc = 0;

    /**
     * @see FilesExtractorInterface::__construct
     */
    public function __construct(FilesInterface $files)
    {
        $this->files = $files;
    }

    /**
     * @see FilesExtractorInterface::extract
     */
    public function extract()
    {
        exec('git rev-parse --verify HEAD 2> /dev/null', $this->output, $this->rc);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if ($this->rc === 0) {
            $against = 'HEAD';
        }

        exec("git diff-index --cached --name-status $against | egrep '^(A|M)' | awk '{print $2;}'", $this->output, $this->rc);

        if (0 !== $this->rc) {
            throw new \RuntimeException(sprintf('Extracting commited files exit with %i status', $this->rc));
        }

        $this->files->setRef($this->output[0]);
        $this->files->setFiles(array_slice($this->output, 1));

        return $this->files;
    }

    public function getTmpFile($file)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'tmp-php-git-hooks-filename');
        exec("git show $this->gitRef:$file > $tmpFile", $this->output, $this->rc);

        if (0 !== $this->rc) {
            throw new \RuntimeException(sprintf(
                'Extracting commited file from rev %s exit with %i status', $this->gitRef, $this->rc
            ));
        }

        return $tmpFile;
    }
}
