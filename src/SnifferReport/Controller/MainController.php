<?php

namespace SnifferReport\Controller;

use SnifferReport\Exception\InvalidStandardsException;
use SnifferReport\Exception\GitCloneException;
use SnifferReport\Exception\MissingFileOrGitParameterException;
use SnifferReport\Model\Sniff;
use SnifferReport\Service\OptionsParser;
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
        $standards = $request->get('standards');
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

        if (!Validator::areAllStandardsValid($standards)) {
            throw new InvalidStandardsException();
        }

        $parsed_options = OptionsParser::parseOptions($options);
        $sniff_result = Sniffer::sniffFiles($files, $standards, $parsed_options);
        // Delete all files that were generated in this request.
        $fs = new Filesystem();
        $fs->remove(FILES_DIRECTORY_ROOT);

        return SniffParser::parseSniff($sniff_result);
    }
}
