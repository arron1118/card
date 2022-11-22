<?php
declare (strict_types = 1);

namespace app\common\controller;

use app\BaseController;
use app\common\library\Aes;
use app\admin\model\User as UserModel;
use think\Request;
use app\common\library\Wx;

class ApiController extends BaseController
{
    use \app\common\traits\JumpTrait;

    /**
     * 无需登录的接口
     * @var string[]
     */
    protected array $noNeedLogin = ['login', 'getAesEncodeData', 'getAesDecodeData', 'getSiteInfo', 'getAccessToken', 'index'];

    /**
     * 用户信息
     * @var null
     */
    protected $userInfo = null;

    /**
     * 用户模型
     * @var null
     */
    protected $UserModel = null;

    /**
     * 当前模块的模型
     * @var null
     */
    protected $model = null;

    /**
     * 请求参数
     * @var array
     */
    protected array $params = [];

    /**
     * api token
     * @var null
     */
    protected $openid = null;

    /**
     * 加密/解密模型
     * @var null
     */
    protected $aes = null;

    /**
     * 是否加密传输
     * @var bool
     */
    protected bool $needAes = false;

    /**
     * api接口返回数据
     * @var array
     */
    public array $returnData = [
        'code' => 0,
        'data' => [],
        'msg' => 'success',
    ];

    protected function initialize():void
    {
        parent::initialize();

        $this->aes = new Aes();
        $this->UserModel = UserModel::class;
        $this->params = $this->getRequestParams();
        $this->openid = $this->params['openid'] ?? null;
        $action = $this->request->action();

        if (!in_array($action, $this->noNeedLogin, true)) {
            $this->returnData['code'] = 5003;
            if (!$this->openid) {
                $this->returnApiData('权限不足：未登录');
            }

            $this->userInfo = UserModel::where('openid', $this->openid)->find();
            if (!$this->userInfo) {
                $this->returnApiData('用户不存在或未登录');
            }

            if (!$this->userInfo->getData('status')) {
                $this->returnApiData(lang('Account is locked'));
            }

            $this->returnData['code'] = 1;
            $this->returnData['userInfo'] = $this->userInfo;
        }
    }

    /**
     * 输出结果集并退出程序
     *
     * @param string $msg
     */
    protected function returnApiData(string $msg = ''): void
    {
        if ($msg !== '') {
            $this->returnData['msg'] = $msg;
        }

        if ($this->returnData['data'] && $this->needAes) {
            $this->returnData['data'] = $this->aes->aesEncode(json_encode($this->returnData['data'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
        }

        response($this->returnData, 200, [], 'json')->send();
        exit;
    }

    /**
     * 获取加密的请求数据
     *
     * @param string $param
     * @return mixed
     */
    protected function getRequestParams(string $param = '')
    {
        if ($this->needAes) {
            $data = $this->request->param($param);
//        if (!$data) {
//            $this->returnApiData('未提供正确的参数');
//        }

            if ($data) {
                return $this->paramFilter(json_decode($this->aes->aesDecode($data), true));
            }

            return [];
        }

        return $this->paramFilter($this->request->param());
    }

    /**
     * 参数过滤
     *
     * @param $param
     * @return mixed
     */
    protected function paramFilter($param)
    {
        if (isset($param['page'])) {
            $param['page'] = (int) $param['page'];
        }

        if (isset($param['limit'])) {
            $param['limit'] = (int) $param['limit'];
        }

        return $param;
    }

    /**
     * 重写验证规则
     * @param array $data
     * @param array|string $validate
     * @param array $message
     * @param bool $batch
     * @return bool|true
     */
    public function validate(array $data, $validate, array $message = [], bool $batch = false): bool
    {
        try {
            parent::validate($data, $validate, $message, $batch);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return true;
    }

    public function getAccessToken()
    {
        $token = (new Wx())->getAccessToken();
        $this->returnData['data'] = $token;
        $this->returnApiData('success');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
