<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\common\controller\ApiController;

class Index extends ApiController
{
    protected function initialize(): void
    {
        parent::initialize(); // TODO: Change the autogenerated stub

    }

    public function index()
    {
        return '您好！这是一个[api]示例应用';
    }
}
