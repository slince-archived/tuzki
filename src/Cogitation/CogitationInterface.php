<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki\Cogitation;

use Slince\Tuzki\Question;
use Slince\Tuzki\Answer;

interface CogitationInterface
{
    /**
     * 思考答案，思考失败返回false
     * @param Question $message
     * @return bool|Answer
     */
    function cogitate(Question $message);
}