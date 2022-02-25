# 用来处理http请求 #
## 安装 ##
	composer require caijw/http
## 使用 ##
	$client = new \Caijw\HttpClient\Client();
	$message = $client->post($url, ['k1' => 'v1', 'k2'=> 'v2']);//使用post提交到某个url上
	if ($message === false) {
	  $exception = $client->getLastException();//异常
	}
## 类方法 ##
	/**
     * @param string $url
     * @param array $data
     * @return false|string 如果成功返回字符串，失败返回false，所以要用===来判断
     * @throws SystemException
     */
    public function get($url, array $data = array());
	public function post($url, array $data = array());
## 特殊情况 ##
默认情况下，连接超时时间为0.4秒，总体请求超时时间为1秒，如果有必要可以通过setOption调整。  
一般情况不用处理返回的Response，如果真的需要，可以使用$this->getLastResponse来获取。如果出现异常，则调用$ex->getResponse()来获取。  
当请求出现问题的时候会在/tmp/目录下生成一个以SE-域名为名字开头的临时文件，当问题消失时文件会被删除（用做监控）

## 钉钉消息
    /**
    * $robotCode是机器人码，不是url
    * $at 可以传三个值
    *   true  : @所有人
    *   被@人的手机号列表
    *   false : 不@人
    */
    $ding = new \Caijw\HttpClient\Ding($robotCode,$at);
    $ding->text('content');
    
### 类方法
    /**
     * @method bool text(string $content)
     * @method bool link(string $title, string $text, string $messageUrl, string $picUrl)
     * @method bool markdown(string $title, string $text)
     */

    


