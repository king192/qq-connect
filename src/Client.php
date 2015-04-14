<?php
namespace QQConnect;

/**
 * 用于向QQ发起请求
 */
class Client
{
    protected $app_id;
    protected $app_key;
    protected $callback;
    protected $endpoint_authorization_code = 'https://graph.qq.com/oauth2.0/authorize';
    protected $endpoint_access_token = 'https://graph.qq.com/oauth2.0/token';

    /**
     * @param array $options
     * @throws \Exception
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['app_id']) || !isset($options['app_key'])) {
            throw new \Exception(_('You must app id,key'));
        }

        $this->app_id = $options['app_id'];
        $this->app_key = $options['app_key'];
        $this->callback = $options['callback'];

        if (isset($options['endpoint'])) {
            $this->endpoint = $options['endpoint'];
        }
        if (isset($options['debug'])) {
            $this->debug = (bool)$options['debug'];
        }
    }

    public function generateRedirectUrl($state, $scope = '')
    {
        $request = [
            'response_type' => 'code',
            'client_id' => $this->app_id,
            'redirect_uri' => $this->callback,
            'state' => $state
        ];

        return $this->endpoint_authorization_code . '?' . http_build_query($request);
    }

    public function getAccessToken($code)
    {
        $request = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->app_id,
            'client_secret' => $this->app_key,
            'redirect_uri' => $this->callback,
            'code' => $code
        ];

        $url = $this->endpoint_authorization_code . '?' . http_build_query($request);
        /**
         * Perform Request
         */
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);

        return $result;
    }
}
