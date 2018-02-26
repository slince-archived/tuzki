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

use Slince\SmartQQ\Client;
use Slince\SmartQQ\EntityCollection;
use Slince\SmartQQ\Exception\ResponseException;
use Slince\SmartQQ\Message\Request;
use Slince\SmartQQ\Message\Response;
use Slince\Tuzki\Exception\RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QQTuzki extends Application
{
    /**
     * 兔斯基名
     * @var string
     */
    protected $tuzkiName;

    /**
     * 监听的qq群
     * @var EntityCollection
     */
    protected $listenGroups = [];

    /**
     * 监听的讨论组
     * @var EntityCollection
     */
    protected $listenDiscuses = [];

    /**
     * 监听的好友
     * @var EntityCollection
     */
    protected $listenFriends = [];

    /**
     * @var Client
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
     * 消息发送最大尝试次数
     * @var int
     */
    protected $maxAttempts = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('正在获取好友信息...');
        $this->listenFriends = $this->smartQQ->getFriends();
        $output->writeln('正在获取群信息...');
        $this->listenGroups = $this->smartQQ->getGroups();
        $output->writeln('正在获取讨论组信息...');
        $this->listenDiscuses = $this->smartQQ->getDiscusses();

        return parent::doRun($input, $output);
    }

    protected function verifyCredential()
    {
    }

    /**
     * 设置安静模式
     *
     * @param boolean $quiteMode
     */
    public function setQuiteMode($quiteMode)
    {
        $this->quiteMode = $quiteMode;
    }

    /**
     * @return bool
     */
    public function isQuiteMode()
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
            } catch (ResponseException $e) { //响应异常忽略重新执行，初次之外的异常需要通知
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
     * 过滤出需要处理的消息
     * @param Response\Message[] $messages
     * @return Response\Message[]
     */
    protected function filterMessages(array $messages)
    {
        $filterMessages = [];
        foreach ($messages as $message) {

            //好友消息、非安静模式、以及提到兔斯基姓名的消息需要回复
            $pass = $message instanceof Response\FriendMessage || !$this->quiteMode
                || strpos((string)$message->getContent(), $this->name) !== false;
            $pass && $filterMessages[] = $message;
        }
        return $filterMessages;
    }

    /**
     * 处理消息
     * @param Response\Message $message
     * @return bool|mixed
     */
    protected function handleMessage(Response\Message $message)
    {
        $times = $this->maxAttempts;
        $success = false;
        $callable = $this->getMessageHandler($message);
        while (!$success) {
            $question = new Question($message->getContent(), $message->getFromUin());
            $answer = $this->tuzki->listen($question)->answer();
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
     * @param Response\Message $message
     * @return \Closure
     */
    protected function getMessageHandler(Response\Message $message)
    {
        $callable = null;
        $class = get_class($message);
        switch ($class) {
            case Response\FriendMessage::class:
                $callable = function(Answer $answer) use ($message){
                    $friend = $this->listenFriends->firstByAttribute('uin', $message->getFromUin());
                    $message = new Request\FriendMessage($friend, $answer->getContent());
                    return $this->smartQQ->sendMessage($message);
                };
                break;
            case Response\DiscussMessage::class:
                $callable = function(Answer $answer) use ($message){
                    $discuz = $this->listenDiscuses->firstByAttribute('uin', $message->getSendUin());
                    $message = new Request\DiscussMessage($discuz, $answer->getContent());
                    return $this->smartQQ->sendMessage($message);
                };
                break;
            case Response\GroupMessage::class:
                $callable = function(Answer $answer) use ($message){
                    $group = $this->listenDiscuses->firstByAttribute('uin', $message->getFromUin());
                    $message = new Request\GroupMessage($group, $answer->getContent());
                    return $this->smartQQ->sendMessage($message);
                };
        }
        return $callable;
    }
}