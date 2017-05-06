<?php

namespace SnifferReport\Service;

use Gitonomy\Git\Admin;
use Gitonomy\Git\Repository;

abstract class GitHandler
{

    /**
     * Handles git URLs.
     *
     * @param $git_url
     *
     * @return array
     */
    public static function handle($git_url)
    {
        $parts = explode(' ', $git_url);
        $url = $parts[0];
        $branch = isset($parts[1]) ? $parts[1] : '';
        return self::getFilesFromGitRepository($url, $branch);
    }

    /**
     * Get files from a given git repository and branch (optional).
     *
     * @param $url
     * @param string $branch
     *
     * @return array
     */
    private static function getFilesFromGitRepository($url, $branch = '')
    {
        $repo = self::cloneGitRepository($url, $branch);
        return FilesHandler::scanFolder($repo->getPath());
    }

    /**
     * Clones a git repository.
     *
     * @param $url
     * @param string $branch
     *
     * @return Repository
     */
    private static function cloneGitRepository($url, $branch = '')
    {
        // Use shallow clone to save time.
        $args = ['--depth', '1'];

        if (!empty($branch)) {
            // Clone from specific branch.
            $args[] = '--branch';
            $args[] = $branch;
        }

        $repository = Admin::cloneRepository(FILES_DIRECTORY_ROOT, $url, $args);
        return $repository;
    }
}
