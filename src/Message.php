<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

class Message implements MessageInterface
{
    /**
     * 消息内容
     * @var string
     */
    protected $content;

    function __construct($content)
    {
        $this->content = $content;
    }

    function __toString()
    {
        return strval($this->content);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}