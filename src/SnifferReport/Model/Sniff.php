<?php

namespace SnifferReport\Model;

class Sniff implements \JsonSerializable
{
    private $files = [];

  /**
   * Adds a file to the Sniff
   *
   * @param File $file
   */
    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

  /**
   * @return File[]
   */
    public function getFiles()
    {
        return $this->files;
    }

  /**
   * {@inheritdoc}
   */
    public function jsonSerialize()
    {
        return [
            'files' => $this->getFiles()
        ];
    }
}
