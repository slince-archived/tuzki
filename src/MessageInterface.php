<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

interface MessageInterface
{
    /**
     * 获取消息内容
     * @return string
     */
    function getContent();
}