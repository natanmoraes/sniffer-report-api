<?php

namespace SnifferReport\Model;


class File implements \JsonSerializable {
  private $name;
  private $messages = [];

  public function __construct($name) {
    $this->setName($name);
  }

  /**
   * Adds a new message to the sniffed file.
   *
   * @param Message $message
   */
  public function addMessages(Message $message) {
    $this->messages[] = $message;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return Message[]
   */
  public function getMessages() {
    return $this->messages;
  }

  /**
   * {@inheritdoc}
   */
  function jsonSerialize() {
    return [
      $this->getName() => [
        'messages' => $this->getMessages(),
      ]
    ];
  }
}
