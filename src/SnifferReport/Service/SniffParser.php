<?php

namespace SnifferReport\Service;

use SnifferReport\Model\Sniff;
use SnifferReport\Model\File;
use SnifferReport\Model\Message;

class SniffParser
{

    /**
     * Parses CodeSniffer results into a Sniff object.
     *
     * @param $sniff_results
     *
     * @return Sniff
     */
    public function parseSniff($sniff_results)
    {
        $sniff = new Sniff();
        foreach ($sniff_results as $file_name => $sniff_result) {
            $name_prefix = FILES_DIRECTORY_ROOT . '/';
            $file_name = str_replace($name_prefix, '', $file_name);
            $file = $this->parseFile($file_name, $sniff_result);
            $sniff->addFile($file);
        }
        return $sniff;
    }

    /**
     * Parses a file from the CodeSniffer result into an object.
     *
     * @param $file_name
     * @param $sniff_result
     *
     * @return File
     */
    private function parseFile($file_name, $sniff_result)
    {
        $file = new File($file_name);
        foreach ($sniff_result->messages as $sniff_message) {
            $message_obj = $this->parseMessage($sniff_message);
            $file->addMessages($message_obj);
        }
        return $file;
    }

    /**
     * Parses a message from the CodeSniffer file into an object.
     *
     * @param $sniff_message
     *
     * @return Message
     */
    private function parseMessage($sniff_message)
    {
        $message = $sniff_message->message;
        $source = $sniff_message->source;
        $severity = $sniff_message->severity;
        $type = $sniff_message->type;
        $line = $sniff_message->line;
        $column = $sniff_message->column;
        $fixable = $sniff_message->fixable;
        $message_obj = new Message($message, $source, $severity, $type, $line, $column, $fixable);
        return $message_obj;
    }
}
