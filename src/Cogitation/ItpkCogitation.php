<?php
/**
 * Created by PhpStorm.
 * User: ACER
 * Date: 2016/8/25
 * Time: 12:20
 */

namespace Slince\Tuzki\Cogitation;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Slince\Tuzki\Message;

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
    }

    /**
     * @param Message $message
     * @return bool|Message
     */
    function cogitate(Message $message)
    {
        $request = new Request('GET', $this->api);
        try {
            $response = $this->httpClient->send($request, [
                'query' => [
                    'api_key' => $this->key,
                    'api_secret' => $this->secret,
                    'question' => $message
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return new Message(strval($response->getBody()));
            }
        } catch (\Exception $e) {
        }
        return false;
    }
}