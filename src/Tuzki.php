<?php
/*
 * This file is part of the slince/tuzki package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Tuzki;

use Slince\Tuzki\Cogitation\CogitationInterface;
use Slince\Tuzki\Exception\InvalidArgumentException;

class Tuzki
{
    /**
     * 思考方式,图灵，茉莉机器人
     * @var CogitationInterface
     */
    protected $cogitation;

    /**
     * 所有的提问
     * @var Message[]
     */
    protected $questions = [];

    public function __construct(CogitationInterface $cogitation = null)
    {
        $this->cogitation = $cogitation;
    }

    /**
     * 听问题
     * @param $question
     * @return $this
     */
    public function listen($question)
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
    public function answer()
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