<?php

namespace LPTracker;

use LPTracker\authentication\AccessToken;
use LPTracker\exceptions\LPTrackerSDKException;

abstract class LPTrackerBase
{
    /**
     * Версия апи
     */
    const VERSION = '1.0';

    /**
     * Адрес апи по умолчанию
     */
    const DEFAULT_ADDRESS = 'https://direct.lptracker.ru';

    /**
     * Название сервиса по умолчанию
     */
    const DEFAULT_SERVICE_NAME = 'PHP SDK';

    /**
     * Ключ переменной окружения логина/email главного аккаунта
     */
    const LOGIN_ENV_NAME = 'LPTRACKER_LOGIN';

    /**
     * Ключ переменной окружения пароля главного аккаунта
     */
    const PASSWORD_ENV_NAME = 'LPTRACKER_PASSWORD';

    /**
     * Ключ переменной окружения имени сервиса
     */
    const SERVICE_NAME_ENV_NAME = 'LPTRACKER_SERVICE_NAME';

    /**
     * Ключ переменной окружения access token от системы если уже есть
     */
    const TOKEN_ENV_NAME = 'LPTRACKER_TOKEN';

    /**
     * Ключ переменной окружения адреса апи
     */
    const ADDRESS_ENV_NAME = 'LPTRACKER_ADDRESS';

    /**
     * @var AccessToken
     */
    protected $token;

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     * @throws LPTrackerSDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge(
            [
                'login' => getenv(static::LOGIN_ENV_NAME),
                'password' => getenv(static::PASSWORD_ENV_NAME),
                'service' => getenv(static::SERVICE_NAME_ENV_NAME),
                'token' => getenv(static::TOKEN_ENV_NAME),
                'address' => getenv(static::ADDRESS_ENV_NAME),
            ],
            $config
        );
        if (empty($config['token'])) {
            if (empty($config['login'])) {
                throw new LPTrackerSDKException(
                    'Required "login" key not supplied in config and could not find fallback environment variable "' . static::LOGIN_ENV_NAME . '"'
                );
            }

            if (empty($config['password'])) {
                throw new LPTrackerSDKException(
                    'Required "password" key not supplied in config and could not find fallback environment variable "' . static::PASSWORD_ENV_NAME . '"'
                );
            }
        }
        if (empty($config['address'])) {
            $config['address'] = self::DEFAULT_ADDRESS;
        }
        if (empty($config['service'])) {
            $config['service'] = self::DEFAULT_SERVICE_NAME;
        }
        $this->config = $config;
        $this->address = $config['address'];
        if (empty($config['token'])) {
            $this->token = $this->login($config['login'], $config['password'], $config['service']);
        } else {
            $this->token = new AccessToken($config['token']);
        }
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token->getValue();
    }

    /**
     * @param $token
     * @return $this
     * @throws LPTrackerSDKException
     */
    public function setToken($token)
    {
        if ($token instanceof AccessToken) {
            $this->token = $token;
        } elseif (is_string($token)) {
            $this->token = new AccessToken($token);
        } else {
            throw new LPTrackerSDKException('Invalid token');
        }
        return $this;
    }

    /**
     * @param array $curlOptions
     */
    public function setCurlOptions($curlOptions)
    {
        LPTrackerRequest::$curlOptions = $curlOptions;
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $serviceName
     * @return mixed
     */
    abstract public function login($login, $password, $serviceName = '');
}
