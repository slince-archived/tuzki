<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

use Slince\Tuzki\Cogitation\CogitationInterface;
use Slince\Tuzki\Exception\InvalidArgumentException;

class Tuzki
{
    /**
     * 思考方式
     * @var CogitationInterface
     */
    protected $cogitation;

    /**
     * 所有的提问
     * @var Message[]
     */
    protected $questions = [];

    function __construct(CogitationInterface $cogitation)
    {
        $this->cogitation = $cogitation;
    }

    /**
     * @param $message
     * @return $this
     */
    function listen($message)
    {
        $this->questions[] = new Question($message);
        return $this;
    }

    /**
     * 回答
     * @return bool|Answer
     */
    function answer()
    {
        $question = end($this->questions);
        if ($question === false) {
            throw new InvalidArgumentException("Cannot find question");
        }
        return $this->cogitation->cogitate($question);
    }
}