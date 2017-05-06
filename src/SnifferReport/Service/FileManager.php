<?php

namespace SnifferReport\Service;

use SnifferReport\Model\File;

class FileManager
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

  /**
   * Creates a file entry in the database and add its ID to the object.
   *
   * @param File $file
   * @param $sniff_id
   */
    public function createFile(File $file, $sniff_id)
    {
        $query = $this->pdo->prepare("
      INSERT INTO file (`name`, `sniff`)
      VALUES (:name, :sniff)
     ");

        $query->execute([
        ':name' => $file->getName(),
        ':sniff' => $sniff_id,
        ]);

        $file->setFileId($this->pdo->lastInsertId());
    }
}
