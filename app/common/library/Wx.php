<?php

namespace app\common\library;

use app\admin\model\SystemConfig;
use Curl\Curl;
use think\facade\Log;

class Wx
{
    private array $errCode = [
        -41016 => 'base64解密失败',
        -41005 => 'base64加密失败',
        -41004 => '解密后得到的buffer非法',
        -41003 => 'aes 解密失败',
        -41002 => '',
        -41001 => 'encodingAesKey 非法',
        -1 => '系统繁忙',
        0 => '请求成功',
        40001 => '获取 access_token 时 AppSecret 错误，或者 access_token 无效',
        40013 => '不合法的 AppID',
        40029 => 'code无效',
        40226 => '高风险等级用户，小程序登录拦截',
        45011 => 'API调用太频繁，请稍候再试',
    ];

    private array $config = [
        'appId' => '',
        'appSecret' => '',
    ];

    public function __construct()
    {
        $this->config['appId'] = SystemConfig::where('name', 'miniapp_appid')->value('value');
        $this->config['appSecret'] = SystemConfig::where('name', 'miniapp_appsecret')->value('value');
    }

    /**
     * @return mixed
     * @throws \JsonException
     * {
            "access_token":"ACCESS_TOKEN",
            "expires_in":7200
        }
     */
    public function getAccessToken()
    {
        if (!session('?wx_access_token') || session('wx_access_token.expires_in') < time()) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token';
            $res = $this->send($url, 'get', [
                'grant_type' => 'client_credential',
                'appid' => $this->config['appId'],
                'secret' => $this->config['appSecret']
            ]);
            $res['expires_in'] = time() + $res['expires_in'];
            session('wx_access_token', $res);
        }

        return session('wx_access_token');
    }

    /**
     * @param $code
     * @throws \JsonException
     * {
            "openid":"xxxxxx",
            "session_key":"xxxxx",
            "unionid":"xxxxx",
            "errcode":0,
            "errmsg":"xxxxx"
        }
     */
    public function login($code)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        return $this->send($url, 'get', [
            'grant_type' => 'authorization_code',
            'js_code' => $code,
            'appid' => $this->config['appId'],
            'secret' => $this->config['appSecret']
        ]);
    }

    /**
     * @param $code
     * @return mixed
     * @throws \JsonException
     * {
            "errcode":0,
            "errmsg":"ok",
            "phone_info": {
                "phoneNumber":"xxxxxx",
                "purePhoneNumber": "xxxxxx",
                "countryCode": 86,
                "watermark": {
                    "timestamp": 1637744274,
                    "appid": "xxxx"
                }
            }
        }
     */
    public function getUserPhoneNumber ($code) {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=' . $access_token['access_token'];
        return $this->send($url, 'post', [
            'code' => $code
        ]);
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) !== 24) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);


        if (strlen($iv) !== 24) {
            return -41002;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj === NULL) {
            return -41003;
        }
        if ($dataObj->watermark->appid !== $this->config['appId']) {
            return -41003;
        }
        $data = $dataObj;
        return 0;
    }

    /**
     * @throws \JsonException
     */
    protected function send($url, $method = 'get', $param = [])
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);   // 不验证证书
        $method === 'get' ? $curl->get($url, $param) : $curl->post($url, $param, true);
        return json_decode($curl->response, true);
    }

    /**
     * @param int $code
     * @return mixed|string
     */
    public function getErrMesaage($code = 0) {
        return $this->errCode[$code];
    }
}
