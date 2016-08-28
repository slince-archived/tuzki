<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

class Question extends Message
{
    /**
     * 问题的提出者
     * @var string
     */
    protected $owner;

    /**
     * 回答
     * @var Answer
     */
    protected $answer;

    function __construct($content, $owner)
    {
        parent::__construct($content);
        $this->owner = $owner;
    }

    /**
     * 是否已经回答
     * @return bool
     */
    function isAnswered()
    {
        return $this->answer instanceof Answer;
    }

    /**
     * @return Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param Answer $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
}