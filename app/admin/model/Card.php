<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Card extends TimeModel
{

    protected $name = "card";

    protected $deleteTime = "delete_time";


    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'admin_id', 'id');
    }

    public function userCardCall()
    {
        return $this->hasMany(UserCardCall::class);
    }

    public function userCardHistory()
    {
        return $this->hasMany(UserCardHistory::class);
    }

    public function userCardShare()
    {
        return $this->hasMany(UserCardShare::class);
    }

    public function userCardSupport()
    {
        return $this->hasMany(UserCardSupport::class);
    }

    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }


}
