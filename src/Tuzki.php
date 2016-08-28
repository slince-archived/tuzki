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
     * 听问题
     * @param $question
     * @return $this
     */
    function listen($question)
    {
        //如果是普通的消息
        if (!$question instanceof Question) {
            $question = new Question($question, uniqid());
        }
        $this->questions[] = $question;
        return $this;
    }

    /**
     * 回答问题
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

    /**
     * 获取当前思考方式
     * @return CogitationInterface
     */
    public function getCogitation()
    {
        return $this->cogitation;
    }

    /**
     * 更换思考方式
     * @param CogitationInterface $cogitation
     */
    public function setCogitation($cogitation)
    {
        $this->cogitation = $cogitation;
    }
}