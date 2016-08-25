<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki\Cogitation;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Slince\Tuzki\Question;
use Slince\Tuzki\Answer;

class TulingCogitation extends AbstractCogitation
{
    /**
     * 思考方式命名
     * @var string
     */
    const NAME = 'tuling';

    protected $api = 'http://www.tuling123.com/openapi/api';

    protected $key;

    /**
     * @var Client
     */
    protected $httpClient;

    function __construct($key)
    {
        $this->key = $key;
        $this->httpClient = new Client([
            'proxy' => 'tcp://127.0.0.1:8888'
        ]);
    }

    /**
     * @param Question $question
     * @return false|Question
     */
    function cogitate(Question $question)
    {
        $request = new Request('POST', $this->api);
        try {
            $response = $this->httpClient->send($request, [
                'form_params' => [
                    'key' => $this->key,
                    'info' => strval($question),
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $jsonData = \GuzzleHttp\json_decode($response->getBody(), true);
                $message = $jsonData['text'];
                switch ($jsonData['code']) {
                    case 40001:
                    case 40002:
                    case 40007:
                    case 40004:
                        $message = "聊累了，明天请早吧~";
                        break;
                }
                $answer = new Answer($message, $question);
                $question->setAnswer($answer);
                return $answer;
            }
        } catch (\Exception $e) {
        }
        return false;
    }
}