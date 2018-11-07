<?php

namespace Caijw\HttpClient;

use BadMethodCallException;

/**
 * @method bool text(string $content)
 * @method bool link(string $title, string $text, string $messageUrl, string $picUrl)
 * @method bool markdown(string $title, string $text)
 *
 */
class Ding
{
    private $url;

    private $data;

    public function __construct($robotCode, $at)
    {
        $this->setRobotCode($robotCode);
        $this->setAt($at);
    }

    public function setRobotCode($robotCode)
    {
        $this->url = "https://oapi.dingtalk.com/robot/send?access_token=$robotCode";
    }

    public function setAt($at)
    {
        if (is_bool($at)) {
            $this->data['at'] = ["isAtAll" => $at];
        } else {
            if (!is_array($at)) {
                $at = [$at];
            }
            $this->data['at'] = ["atMobiles" => $at];
        }
    }

    public function __call($method, $arguments)
    {
        $message = [];
        switch ($method) {
            case 'text':
                $message['content'] = $arguments[0];
                break;
            case 'link':
                $message['title'] = $arguments[0];
                $message['text'] = $arguments[1];
                $message['messageUrl'] = $arguments[2];
                $message['picUrl'] = $arguments[3];
                break;
            case 'markdown':
                $message['title'] = $arguments[0];
                $message['text'] = $arguments[1];
                break;
            default:
                throw new BadMethodCallException(sprintf(
                    'Method %s::%s does not exist.', static::class, $method
                ));
        }

        $this->data['msgtype'] = $method;
        $this->data[$method] = $message;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


}
