<?php

namespace PhpGitHooks\Infrastructure\Git;

/**
 * Interface FilesExtractorInterface.
 */
interface FilesExtractorInterface
{
    /**
     * @param FilesInterface $file
     */
    public function __construct(FilesInterface $files);

    /**
     * return FilesInterface
     */
    public function extract();
}
