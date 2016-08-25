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

class ItpkCogitation extends AbstractCogitation
{
    protected $api = 'http://i.itpk.cn/api.php';

    protected $key;

    protected $secret;

    /**
     * @var Client
     */
    protected $httpClient;

    function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
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
        $request = new Request('GET', $this->api);
        try {
            $response = $this->httpClient->send($request, [
                'query' => [
                    'api_key' => $this->key,
                    'api_secret' => $this->secret,
                    'question' => strval($question)
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $answer = new Answer(strval($response->getBody()), $question);
                $question->setAnswer($answer);
                return $answer;
            }
        } catch (\Exception $e) {
        }
        return false;
    }
}