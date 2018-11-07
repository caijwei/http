<?php

namespace Caijw\HttpClient;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private $guzzleClient;
    private $config = array();
    /**
     * @var Response
     */
    private $lastResponse;
    private $lastException;

    public function __construct()
    {
        $config = array(
            RequestOptions::TIMEOUT => 1.0,
            RequestOptions::CONNECT_TIMEOUT => 0.4,
            RequestOptions::ALLOW_REDIRECTS => false,
        );
        $this->config = $config;
    }

    /**
     * 设置额外的属性
     * 属性一般情况只能是\GuzzleHttp\RequestOptions中的常数
     * 没有校验，谨慎使用
     * @param string $name
     * @param mixed $value
     */
    public function setOption($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * get 获取
     * @param string $url
     * @param array $data
     * @return false|string 如果成功返回字符串，失败返回false，所以要用===来判断
     * @throws SystemException
     */
    public function get($url, array $data = array(), array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        if (count($data)) {
            if (strpos($url, '?')) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= http_build_query($data);
        }
        try {
            $response = $this->getGuzzleClient()->get($url, [
                RequestOptions::HEADERS => $headers
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * post 创建
     * @param $url
     * @param array $data
     * @param array $headers
     * @return false|string
     * @throws SystemException
     */
    public function post($url, array $data = array(), array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        try {
            $response = $this->getGuzzleClient()->post($url, [
                RequestOptions::FORM_PARAMS => $data,
                RequestOptions::HEADERS => $headers,
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * 传输文件
     * @param $url
     * @param array $data
     * @param array $headers
     * @return false|string
     * @throws SystemException
     */
    public function postFile($url, array $data, array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        try {
            $response = $this->getGuzzleClient()->post($url, [
                RequestOptions::MULTIPART => $data,
                RequestOptions::HEADERS => $headers,
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }


    /**
     * put 更新
     * @param $url
     * @param array $data
     * @param array $headers
     * @return false|string
     * @throws SystemException
     */
    public function put($url, $data = '', array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        try {
            $response = $this->getGuzzleClient()->put($url, [
                RequestOptions::BODY => $data,
                RequestOptions::HEADERS => $headers
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * head
     * @param $url
     * @param array $headers
     * @return false|string
     * @throws SystemException
     */
    public function head($url, array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        try {
            $response = $this->getGuzzleClient()->head($url, [
                RequestOptions::HEADERS => $headers
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }

    public function delete($url, $data = '', array $headers = array())
    {
        $this->lastResponse = $this->lastException = null;
        try {
            $response = $this->getGuzzleClient()->delete($url, [
                RequestOptions::BODY => $data,
                RequestOptions::HEADERS => $headers
            ]);
            return $this->handleResponse($response);
        } catch (RequestException $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastException()
    {
        return $this->lastException;
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function handleResponse(ResponseInterface $response)
    {
        $this->lastResponse = $response;
        return (string)$response->getBody();
    }

    /**
     * @param RequestException $ex
     * @return false 永远返回false
     * @throws SystemException
     */
    private function handleException(RequestException $ex)
    {
        $this->lastException = $ex;
        return false;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function getGuzzleClient()
    {
        if (!isset($this->guzzleClient)) {
            $this->guzzleClient = new \GuzzleHttp\Client($this->config);
        }
        return $this->guzzleClient;
    }
}
