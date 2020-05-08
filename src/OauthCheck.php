<?php

namespace Rdisme\Xyaccount;


class OauthCheck
{

    // token cookie key
    const TOKEN_CACHE_KEY = '_LT';
    // set the cookie to expire in 30 days
    const TOKEN_CACHE_EXPIRE = 60 * 60 * 24 * 30;

    // url for oauth
    private $oauth_url;
    // oauth callback url
    private $target_url;


    public function __construct($params)
    {
        $this->oauth_url = $params['oauth_url'];
    }


    /**
     * 用于业务入口页面
     * 此方法，应该放在所有业务逻辑之前
     */
    public function sync($oauth_type = 1)
    {
        if (false === $token = $this->async()) {
            $this->send($oauth_type);
        }
        return $token;
    }

    /**
     * use in async
     * eg: ajax
     */
    public function async()
    {
        return $this->_get_token();
    }


    /**
     * refresh token from oauth
     * more details https://code.aliyun.com/cmcc-tibet/account
     */
    public function send($oauth_type = 1)
    {
        $target_url = $this->oauth_url . '&target=' . urlencode($this->_get_target()) . '&type=' . $oauth_type;
        header('location:' . $target_url);
        die;
    }


    // set target url
    public function set_target($target_url)
    {
        $this->target_url = $target_url;
        return $this;
    }


    // return target url
    private function _get_target()
    {
        return $this->target_url;
    }

    /**
     * get token
     * 1、get token from get request
     * 2，get token from cache
     * @return token/false
     */
    private function _get_token()
    {
        // from get
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        if (!empty($token)) {
            $this->_set_token_by_cache($token);
            return $token;
        }
        // from cache
        return $this->_get_token_by_cache();
    }

    /**
     * get token from cache
     * @return token/false
     */
    private function _get_token_by_cache()
    {
        return empty($_COOKIE[self::TOKEN_CACHE_KEY]) ? false : $_COOKIE[self::TOKEN_CACHE_KEY];
    }

    /**
     * set cache token
     * @return true/false
     */
    private function _set_token_by_cache($token)
    {
        return setcookie(self::TOKEN_CACHE_KEY, $token, time() + self::TOKEN_CACHE_EXPIRE, '/');
    }
}
