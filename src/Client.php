<?php
namespace QQConnect;

/**
 * 用于向QQ发起请求
 */
class Client
{
    protected $app_id;
    protected $app_key;
    protected $endpoint_authorization_code = 'https://graph.qq.com/oauth2.0/authorize';

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

        if (isset($options['endpoint'])) {
            $this->endpoint = $options['endpoint'];
        }
        if (isset($options['debug'])) {
            $this->debug = (bool)$options['debug'];
        }
    }

    public function generateRedirectUrl($callback, $scope = '')
    {
        $state = '123456';
        $request = [
            'response_type' => 'code',
            'client_id' => $this->app_id,
            'redirect_uri' => urlencode($callback),
            'state' => $state
        ];

        return $this->endpoint_authorization_code . '?' . http_build_query($request);
    }
}
