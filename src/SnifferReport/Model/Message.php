<?php

namespace SnifferReport\Model;


class Message implements \JsonSerializable {
  private $message;
  private $source;
  private $severity;
  private $type;
  private $line;
  private $column;
  private $fixable;

  public function __construct($message, $source, $severity, $type, $line, $column, $fixable) {
    $this->setMessage($message);
    $this->setSource($source);
    $this->setSeverity($severity);
    $this->setType($type);
    $this->setLine($line);
    $this->setColumn($column);
    $this->setFixable($fixable);
  }

  /**
   * @return mixed
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setMessage($message) {
    $this->message = $message;
  }

  /**
   * @return mixed
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * @param mixed $source
   */
  public function setSource($source) {
    $this->source = $source;
  }

  /**
   * @return mixed
   */
  public function getSeverity() {
    return $this->severity;
  }

  /**
   * @param mixed $severity
   */
  public function setSeverity($severity) {
    $this->severity = $severity;
  }

  /**
   * @return mixed
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getLine() {
    return $this->line;
  }

  /**
   * @param mixed $line
   */
  public function setLine($line) {
    $this->line = $line;
  }

  /**
   * @return mixed
   */
  public function getColumn() {
    return $this->column;
  }

  /**
   * @param mixed $column
   */
  public function setColumn($column) {
    $this->column = $column;
  }

  /**
   * @return mixed
   */
  public function isFixable() {
    return $this->fixable;
  }

  /**
   * @param mixed $fixable
   */
  public function setFixable($fixable) {
    $this->fixable = $fixable;
  }

  /**
   * {@inheritdoc}
   */
  function jsonSerialize() {
    return [
      'message' => $this->getMessage(),
      'source' => $this->getSource(),
      'severity' => $this->getSeverity(),
      'type' => $this->getType(),
      'line' => $this->getLine(),
      'column' => $this->getColumn(),
      'fixable' => $this->isFixable(),
    ];
  }
}
