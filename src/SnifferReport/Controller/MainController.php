<?php

namespace SnifferReport\Controller;

use SnifferReport\Exception\InvalidStandardsException;
use SnifferReport\Exception\GitCloneException;
use SnifferReport\Exception\MissingFileOrGitParameterException;
use SnifferReport\Model\Sniff;
use SnifferReport\Service\Validator;
use SnifferReport\Service\FilesHandler;
use SnifferReport\Service\GitHandler;
use SnifferReport\Service\Sniffer;
use SnifferReport\Service\SniffParser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use \Exception;

class MainController
{
    /**
     * Processes the POSTed data.
     *
     * @param Request $request
     *
     * @return Sniff
     *
     * @throws Exception
     * @throws GitCloneException
     * @throws InvalidStandardsException
     * @throws MissingFileOrGitParameterException
     */
    public static function processSniff(Request $request)
    {
        $file = $request->files->get('file');
        $git_url = $request->get('git_url');
        $options = $request->get('options');

        $valid_git_url = (is_null($git_url) || !Validator::isGitUrl($git_url));
        if ($valid_git_url && is_null($file)) {
            throw new MissingFileOrGitParameterException();
        }

        if (!is_null($file)) {
            $file_name = $file->getClientOriginalName();
            $file->move(FILES_DIRECTORY_ROOT, $file_name);
            $files = FilesHandler::handle($file_name, $file->getClientMimeType());
        } else {
            try {
                $files = GitHandler::handle($git_url);
            } catch (Exception $e) {
                throw new GitCloneException($e);
            }
        }

        // @fixme Temporary
        if (empty($options)) {
            throw new \Exception(
                'Missing options',
                400
            );
        }

        $options = json_decode($options);

        if (!Validator::areStandardsValid($options->standards)) {
            throw new InvalidStandardsException();
        }

        // @fixme: handle extensions and ignoring specific files/folders

        $standards = implode(',', $options->standards);
        $extensions = implode(',', $options->extensions);

        $sniff_result = Sniffer::sniffFiles($files, $standards, $extensions);

        // Delete all files that were generated in this request.
        $fs = new Filesystem();
        $fs->remove(FILES_DIRECTORY_ROOT);

        // @todo: Make class abstract
        $sniffParser = new SniffParser();
        $response = $sniffParser->parseSniff($sniff_result);
        return $response;
    }
}
