<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="user")
 */
class User extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\User();
        
        $this->assign('getGenderList', $this->model->getGenderList());

        $this->assign('getStatusList', $this->model->getStatusList());

    }

    
}