<?php

namespace PhpGitHooks\Infrastructure\Git;

/**
 * Interface FilesInterface.
 */
interface FilesInterface
{
    /**
     * @return array
     */
    public function getFiles();

    /**
     * @param array $files
     */
    public function setFiles(array $files);

    /**
     * @return string
     */
    public function getRef();

    /**
     * @param string $ref
     */
    public function setRef($ref);
}
