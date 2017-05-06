<?php

namespace SnifferReport\Service;

use SnifferReport\Model\Sniff;

class SniffManager
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

  /**
   * Creates a sniff entry in the database and add its ID to the object.
   *
   * @param Sniff $sniff
   */
    public function createSniff(Sniff $sniff)
    {
        $query = $this->pdo->prepare("INSERT INTO sniff VALUES (null)");
        $query->execute();
        $sniff->setSniffId($this->pdo->lastInsertId());
    }
}
