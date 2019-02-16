<?php
/**
 * Created by PhpStorm.
 * User: WeiYahui
 * Date: 2019/2/15
 * Time: 16:37
 */

namespace Doacme\BaiduShorturl;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * Class Console
 * @package Doacme\BaiduShorturl
 */
class Console
{
    /**
     * 静态存储
     * @var array
     */
    private static $store = [
        'client'=>null,
    ];

    private static $config = [
        'host'=>'https://dwz.cn',
        'createUrl'=>'/admin/v2/create',
        'queryUrl'=>'/admin/v2/query',
        'token'=>null,
    ];

    /**
     * Console constructor.
     * @param string $token
     */
    public function __construct($token)
    {
        $token = isset($token) ? $token : '';
        if (!is_string($token)) {
            $token = '';
        }
        self::$config['token'] = $token;
    }

    /**
     * 网络请求客户端
     * @return Client
     */
    private function client()
    {
        if (!isset(self::$store['client'])) {
            self::$store['client'] = new Client([
                'verify'=>false,
                'base_uri'=>self::$config['host'],
            ]);
        }
        return self::$store['client'];
    }

    /**
     * 短网址生成方法
     * @param $url
     * @return string
     * @throws \Exception
     */
    public function create($url)
    {
        try {
            $response = $this->client()->post(self::$config['createUrl'], [
                'headers' => [
                    'Accept'=>'application/json',
                    'Token'=>self::$config['token'],
                ],
                'form_params' => [
                    'url'=>$url,
                ],
            ]);
            $contents = $this->handleResponseOrFail($response);
            $contents = json_decode($contents, true);
            /**返回数据的格式
            {
            "Code": 0,
            "ShortUrl": "https://dwz.cn/de3rp2Fl",
            "LongUrl": "http://www.baidu.com",
            "ErrMsg": ""
            }
             */
            if (isset($contents['Code'])) {
                if ($contents['Code'] == 0 && !empty($contents['ShortUrl'])) {
                    return $contents['ShortUrl'];
                }
            }
            throw new \Exception('获取短网址失败');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 统一处理请求响应
     * @param $response
     * @return string
     * @throws \Exception
     */
    private function handleResponseOrFail($response)
    {
        if ($response->getResponseStatus() == 200) {
            $contents = $response->getBody()->getContents();
            if (is_string($contents) && !empty($contents)) {
                return $contents;
            }
        } else {
            throw new \Exception('网络请求异常');
        }
    }
}
