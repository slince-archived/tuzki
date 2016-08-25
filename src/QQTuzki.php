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
     * 兔斯基名
     * @var string
     */
    protected $name;

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
     * 安静模式
     * @var bool
     */
    protected $quiteMode = false;

    /**
     * 二维码位置
     * @var string
     */
    protected $qrCodePath;

    /**
     * 消息发送最大尝试次数
     * @var int
     */
    protected $maxAttempts = 10;

    public function __construct($name, $qrCodePath, Tuzki $tuzki)
    {
        $this->name = $name;
        $this->qrCodePath = $qrCodePath;
        $this->smartQQ = new SmartQQ();
        $this->tuzki = $tuzki;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $quiteMode
     */
    public function setQuiteMode($quiteMode)
    {
        $this->quiteMode = $quiteMode;
    }

    /**
     * @return bool
     */
    public function getQuiteMode()
    {
        return $this->quiteMode;
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

    /**
     * 监听消息并及时回复
     */
    public function listen()
    {
        $this->smartQQ->login($this->qrCodePath);
        while (true) {
            try {
                $messages = $this->smartQQ->pollMessages();
            } catch (\Exception $e) {
                $messages = [];
            }
            if (empty($messages)) {
                sleep(2);
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
            //好友消息、非安静模式、以及提到兔斯基姓名的消息需要回复
            $pass = $message == Message::TYPE_FRIEND || !$this->quiteMode || strpos($message->content, $this->name) !== false;
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