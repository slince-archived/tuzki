<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki\Cogitation;

use Slince\Tuzki\Message;

interface CogitationInterface
{
    /**
     * 思考答案，思考失败返回false
     * @param Message $message
     * @return bool|string
     */
    function cogitate(Message $message);
}