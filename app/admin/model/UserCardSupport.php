<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class UserCardSupport extends TimeModel
{

    protected $name = "user_card_support";

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