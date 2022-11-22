<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class UserCardShare extends TimeModel
{

    protected $name = "user_card_share";

    protected $deleteTime = "delete_time";

    
    public function card()
    {
        return $this->belongsTo('\app\admin\model\Card', 'card_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('\app\admin\model\User', 'user_id', 'id');
    }

    

}