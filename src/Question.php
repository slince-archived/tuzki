<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

class Question extends Message
{
    /**
     * 回答
     * @var Answer
     */
    protected $answer;

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
}