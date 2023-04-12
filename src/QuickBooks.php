<?php

namespace true9\QuickBooks;

use QuickBooksOnline\API\DataService\DataService;

class QuickBooks
{
    protected $config = [];
    protected $logging = false;
    protected $loggingLocation = null;

    public function __construct()
    {
        $config = config('quickbooks');

        $this->config = [
            'auth_mode' => $config['data_service']['auth_mode'],
            'baseUrl' => $config['data_service']['base_url'],
            'ClientID' => $config['data_service']['client_id'],
            'ClientSecret' => $config['data_service']['client_secret'],
            'RedirectURI' => $config['redirect_url'],
            'scope' => $config['data_service']['scope']
        ];

        $this->logging = $config['logging']['enabled'] ?? false;
        $this->loggingLocation = $config['logging']['location'] ?? null;
    }

    public function getAuthorizationUrl()
    {
        $this->setAccessToken(null);
        $this->setRefreshToken(null);

        return $this->getDataService()
            ->getOAuth2LoginHelper()
            ->getAuthorizationCodeURL();
    }

    public function setAccessToken($token)
    {
        $this->config['accessTokenKey'] = $token;
    }

    public function setRealmId($id)
    {
        $this->config['QBORealmID'] = $id;
    }

    public function setRefreshToken($token)
    {
        $this->config['refreshTokenKey'] = $token;
    }

    public function exchangeAuthorizationCode($code, $realmId)
    {
        $this->setAccessToken(null);
        $this->setRefreshToken(null);

        return $this->getDataService()
            ->getOAuth2LoginHelper()
            ->exchangeAuthorizationCodeForToken($code, $realmId);
    }

    public function getDataService()
    {
        $dataService = DataService::Configure($this->config)
            ->throwExceptionOnError(true);

        if ($this->logging) {
            $dataService->setLogLocation($this->loggingLocation);
            $dataService->enableLog();
        } else {
            $dataService->disableLog();
        }

        return $dataService;
    }

    public function getAccessToken()
    {
        return $this->getDataService()
            ->getOAuth2LoginHelper()
            ->refreshToken();
    }
}
