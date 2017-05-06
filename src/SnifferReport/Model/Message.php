<?php

namespace SnifferReport\Model;

class Message implements \JsonSerializable
{
    private $messageId;
    private $message;
    private $source;
    private $severity;
    private $type;
    private $line;
    private $column;
    private $fixable;

    public function __construct($message, $source, $severity, $type, $line, $column, $fixable)
    {
        $this->setMessage($message);
        $this->setSource($source);
        $this->setSeverity($severity);
        $this->setType($type);
        $this->setLine($line);
        $this->setColumn($column);
        $this->setFixable($fixable);
    }

  /**
   * @return int
   */
    public function getMessageId()
    {
        return $this->messageId;
    }

  /**
   * @param int $message_id
   */
    public function setMessageId($message_id)
    {
        $this->messageId = $message_id;
    }

  /**
   * @return string
   */
    public function getMessage()
    {
        return $this->message;
    }

  /**
   * @param string $message
   */
    public function setMessage($message)
    {
        $this->message = $message;
    }

  /**
   * @return string
   */
    public function getSource()
    {
        return $this->source;
    }

  /**
   * @param string $source
   */
    public function setSource($source)
    {
        $this->source = $source;
    }

  /**
   * @return int
   */
    public function getSeverity()
    {
        return $this->severity;
    }

  /**
   * @param int $severity
   */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

  /**
   * @return string
   */
    public function getType()
    {
        return $this->type;
    }

  /**
   * @param string $type
   */
    public function setType($type)
    {
        $this->type = $type;
    }

  /**
   * @return int
   */
    public function getLine()
    {
        return $this->line;
    }

  /**
   * @param int $line
   */
    public function setLine($line)
    {
        $this->line = $line;
    }

  /**
   * @return int
   */
    public function getColumn()
    {
        return $this->column;
    }

  /**
   * @param int $column
   */
    public function setColumn($column)
    {
        $this->column = $column;
    }

  /**
   * @return boolean
   */
    public function isFixable()
    {
        return $this->fixable;
    }

  /**
   * @param boolean $fixable
   */
    public function setFixable($fixable)
    {
        $this->fixable = $fixable;
    }

  /**
   * {@inheritdoc}
   */
    public function jsonSerialize()
    {
        return [
        'message_id' => $this->getMessageId(),
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
