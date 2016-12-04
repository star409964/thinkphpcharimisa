<?php
class Wechat
{
    private $data = array();
	/**
     * 构造方法，用于实例化微信SDK
     * @param string $token 微信开放平台设置的TOKEN
     */
    public function __construct($token)
    {
        $this->auth($token) || die;
        if (IS_GET) {   //thinkphp 判断是不是GET请求
            echo $_GET['echostr'];
            die;  //exit() 函数输出一条消息，并退出当前脚本。该函数是 die() 函数的别名。
        } else {
            $xml = file_get_contents('php://input');
            $xml = new SimpleXMLElement($xml);
            $xml || die;
            foreach ($xml as $key => $value) {
                $this->data[$key] = strval($value);
            }
        }
    }
	 /**
     * 获取微信推送的数据
     * @return array 转换为数组后的数据
     */
    public function request()
    {
        return $this->data;
    }
    public function response($content, $type = 'text', $flag = 0)
    {
        $this->data = array('ToUserName' => $this->data['FromUserName'], 'FromUserName' => $this->data['ToUserName'], 'CreateTime' => NOW_TIME, 'MsgType' => $type);
        $this->{$type}($content);
        $this->data['FuncFlag'] = $flag;
        $xml = new SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $this->data);
        die($xml->asXML());
    }
    private function text($content)
    {
        $this->data['Content'] = $content;
    }
    private function music($music)
    {
        list($music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl']) = $music;
        $this->data['Music'] = $music;
    }
    private function news($news)
    {
        $articles = array();
        foreach ($news as $key => $value) {
            list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = $value;
            if ($key >= 9) {
                break;
            }
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;
    }
    private function data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            is_numeric($key) && ($key = $item);
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }
	 /**
     * 对数据进行签名认证，确保是微信发送的数据
     * @param  string $token 微信开放平台设置的TOKEN
     * @return boolean       true-签名正确，false-签名错误
     */
    private function auth($token)
    {
        $data = array($_GET['timestamp'], $_GET['nonce'], $token);
        $sign = $_GET['signature'];
        sort($data);
        $signature = sha1(implode($data));
        return $signature === $sign;
    }
}