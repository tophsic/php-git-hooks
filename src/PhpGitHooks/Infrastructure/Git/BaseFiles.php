<?php

namespace PhpGitHooks\Infrastructure\Git;

/**
 * Class BaseFiles.
 */
class BaseFiles implements FilesInterface
{
    /** @var string */
    private $ref;

    /** @var array */
    private $files = [];

    /**
     * @see FilesInterface::getFiles
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @see FilesInterface::setFiles
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @see FilesInterface::getRef
     */
    public function getRef()
    {
        $this->gitRef;
    }

    /**
     * @see FilesInterface::setRef
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    }
}

