<?php

namespace SnifferReport\Model;


class Sniff implements \JsonSerializable {
  private $sniffId;
  private $files = [];

  /**
   * Adds a file to the Sniff
   *
   * @param File $file
   */
  public function addFile(File $file) {
    $this->files[] = $file;
  }

  /**
   * @return int
   */
  public function getSniffId() {
    return $this->sniffId;
  }

  /**
   * @param int $sniff_id
   */
  public function setSniffId($sniff_id) {
    $this->sniffId = $sniff_id;
  }

  /**
   * @return File[]
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * {@inheritdoc}
   */
  function jsonSerialize() {
    return [
      'sniff_id' => $this->getSniffId(),
      'files' => $this->getFiles()
    ];
  }
}
