<?php
/**
 * Created by PhpStorm.
 * User: WeiYahui
 * Date: 2019/2/15
 * Time: 16:37
 */

namespace Doacme\BaiduShorturl;

use GuzzleHttp\Client;

class Console
{
    /**
     * 静态存储
     * @var array
     */
    private static $store = [
        'client'=>null,
        'token'=>null,
    ];

    private static $config = [
        'host'=>'https://dwz.cn',
        'createUrl'=>'/admin/v2/create',
        'queryUrl'=>'/admin/v2/query',
    ];

    /**
     * Console constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $token = isset($token) ? $token : '';
        if (!is_string($token)) {
            $token = '';
        }
        self::$store['token'] = $token;
    }

    /**
     * 网络请求客户端
     * @return Client
     */
    private function client()
    {
        if (!isset(self::$store['client'])) {
            self::$store['client'] = new Client(['verify'=>false]);
        }
        return self::$store['client'];
    }

    public function create()
    {
        $response = $this->client()->post('','');
    }
}
