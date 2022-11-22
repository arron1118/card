<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class User extends TimeModel
{

    protected $name = "user";

    protected $deleteTime = "delete_time";

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

    public function getGenderList()
    {
        return ['0'=>'保密','1'=>'男','2'=>'女',];
    }

    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function getUserInfo($openid, $userInfo) {
        $user = $this->where('openid', $openid)->hidden(['password'])->find();
        if (!$user) {
            $data['openid'] = $openid;
            $data['city'] = $userInfo['city'];
            $data['header_img'] = $userInfo['avatarUrl'];
            $data['country'] = $userInfo['country'];
            $data['province'] = $userInfo['province'];
            $data['nickname'] = $userInfo['nickName'];
            $data['from_card'] = $userInfo['card_id'] ?? 0;
            $data['admin_id'] = Card::where('id', $userInfo['card_id'])->value('admin_id');
            $this->save($data);

            $user = $this;
        }

        return $user;
    }

    public function updatePhone($openid, $phone_info)
    {
        return $this->where(['openid' => $openid])->save(['phone' => $phone_info['phoneNumber']]);
    }
}
