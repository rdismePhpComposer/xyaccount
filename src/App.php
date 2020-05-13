<?php

namespace Rdisme\Xyaccount;


class App
{

    // 合法的appid列表
    private $valid_appids;


    public function __construct($config)
    {
        $this->valid_appids = $config;
    }


    /**
     * appid 的校验
     * 全局唯一
     * 失败返回false
     * 成功返回对应的secret
     */
    public function check($appid)
    {
        return isset($this->valid_appids[$appid]) ? $this->valid_appids[$appid] : false;
    }
}
