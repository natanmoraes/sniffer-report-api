<?php

namespace SnifferReport\Service;


use SnifferReport\Model\Message;

class MessageManager {
  private $pdo;

  public function __construct(\PDO $pdo) {
    $this->pdo = $pdo;
  }

  /**
   * Creates a message entry in the database and add its ID to the object.
   *
   * @param Message $message
   * @param $file_id
   */
  public function createMessage(Message $message, $file_id) {
    $query = $this->pdo->prepare("
      INSERT INTO message (`message`, `source`, `severity`, `type`, `line`, `column`, `fixable`, `file`)
      VALUES (:message, :source, :severity, :type, :line, :column, :fixable, :file)
     ");
    $query->execute([
      ':message' => $message->getMessage(),
      ':source' => $message->getSource(),
      ':severity' => $message->getSeverity(),
      ':type' => $message->getType(

      ),
      ':line' => $message->getLine(),
      ':column' => $message->getColumn(),
      ':fixable' => (int) $message->isFixable(),
      ':file' => $file_id,
    ]);
    $message->setMessageId($this->pdo->lastInsertId());
  }
}
