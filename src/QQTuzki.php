<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

use Slince\SmartQQ\Model\Message;
use Slince\SmartQQ\SmartQQ;
use Slince\Tuzki\Cogitation\ItpkCogitation;

class QQTuzki
{
    /**
     * 监听的qq群
     * @var array
     */
    protected $listenGroups = [];

    /**
     * 监听的讨论组
     * @var array
     */
    protected $listenDiscuses = [];

    /**
     * 监听的好友
     * @var array
     */
    protected $listenFriends = [];

    /**
     * @var SmartQQ
     */
    protected $smartQQ;

    /**
     * @var Tuzki
     */
    protected $tuzki;

    /**
     * 二维码位置
     * @var string
     */
    protected $qrCodePath;

    protected $maxAttempts = 10;

    public function __construct($qrCodePath)
    {
        $this->qrCodePath = $qrCodePath;
        $this->smartQQ = new SmartQQ();
        $this->tuzki = new Tuzki(new ItpkCogitation('27bad0c963b9f4a460dd5e1cb6ad76b0', 'vkq6b8qjot7j'));
    }

    /**
     * @param int $maxAttempts
     */
    public function setMaxAttempts($maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * @return int
     */
    public function getMaxAttempts()
    {
        return $this->maxAttempts;
    }
    
    public function listen()
    {
        $this->smartQQ->login($this->qrCodePath);
        while (true) {
            $messages = $this->smartQQ->pollMessages();
            if (empty($messages)) {
                sleep(1);
            } else {
                foreach ($this->filterMessages($messages) as $message) {
                    $this->handleMessage($message);
                }
            }
        }
    }

    /**
     * @param Message[] $messages
     * @return Message[]
     */
    protected function filterMessages(array $messages)
    {
        $filterMessages = [];
        foreach ($messages as $message) {
            $pass = false;
            switch ($message->type) {
                case Message::TYPE_FRIEND:
                    if (empty($this->listenFriends) ) {
                        $pass = true;
                    }
                    break;
                case Message::TYPE_DISCUS:
                    if (empty($this->listenDiscuses) ) {
                        $pass = true;
                    }
                    break;
                case Message::TYPE_GROUP:
                    if (empty($this->listenGroups) ) {
                        $pass = true;
                    }
                    break;
            }
            $pass && $filterMessages[] = $message;
        }
        return $filterMessages;
    }

    /**
     * 处理消息
     * @param Message $message
     * @return bool|mixed
     */
    protected function handleMessage(Message $message)
    {
        $times = $this->maxAttempts;
        $success = false;
        $callable = $this->getMessageHandler($message);
        while (!$success) {
            $answer = $this->tuzki->listen($message->content)->answer();
            $success = call_user_func($callable, $answer);
            $success || $times --;
            if ($times <= 0) {
                break;
            }
        }
        return $success;
    }

    /**
     * 获取消息处理器
     * @param Message $message
     * @return \Closure
     */
    protected function getMessageHandler(Message $message)
    {
        static $callables = [];
        if (isset($callables[$message->type])) {
            return $callables[$message->type];
        }
        $callable = null;
        switch ($message->type) {
            case Message::TYPE_FRIEND:
                $callable = function(Answer $answer) use ($message){
                    return $this->smartQQ->sendMessageToFriend($message->fromUin, $answer->getContent());
                };
                break;
            case Message::TYPE_DISCUS:
                $callable = function(Answer $answer) use ($message){
                    return $this->smartQQ->sendMessageToDiscus($message->discusId, $answer->getContent());
                };
                break;
            case Message::TYPE_GROUP:
                $callable = function(Answer $answer) use ($message){
                    return $this->smartQQ->sendMessageToGroup($message->fromUin, $answer->getContent());
                };
        }
        $callables[$message->type] = $callable;
        return $callable;
    }
}