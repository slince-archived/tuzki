<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

class Answer extends Message
{
    /**
     * question
     * @var Question
     */
    protected $question;

    function __construct($content, Question $question)
    {
        parent::__construct($content);
        $this->question = $question;
    }
}