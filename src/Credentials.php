<?php

namespace true9\QuickBooks;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;

class Credentials
{
    protected Filesystem $disk;

    protected string $filePath;

    public function __construct(FilesystemManager $storage)
    {
        // TODO: Add a QuickBooks config value to allow this to be customised
        $this->disk = $storage->disk(config('filesystems.default'));
        $this->filePath = 'quickbooks.json';
    }

    public function getData(): array
    {
        return $this->data();
    }

    public function exists(): bool
    {
        return $this->disk->exists($this->filePath);
    }

    public function getAccessToken()
    {
        return $this->data('access_token');
    }

    public function getRealmId()
    {
        return $this->data('realm_id');
    }

    public function getRefreshToken()
    {
        return $this->data('refresh_token');
    }

    public function getExpires()
    {
        return $this->data('access_token_expires_at');
    }

    public function isExpired(): bool
    {
        return time() >= $this->getExpires();
    }

    public function delete()
    {
        if (! $this->exists()) {
            throw new \Exception('QuickBooks credentials are missing');
        }

        return $this->disk->delete($this->filePath);
    }

    public function store(OAuth2AccessToken $token): void
    {
        $result = $this->disk->put($this->filePath, json_encode([
            'access_token' => $token->getAccessToken(),
            'access_token_expires_at' => strtotime($token->getAccessTokenExpiresAt()),
            'realm_id' => $token->getRealmID(),
            'refresh_token' => $token->getRefreshToken(),
            'refresh_token_expires_at' => strtotime($token->getRefreshTokenExpiresAt()),
        ]));

        if (! $result) {
            throw new \Exception('Failed to write to file: '.$this->filePath);
        }
    }

    protected function data($key = null)
    {
        if (! $this->exists()) {
            throw new \Exception('QuickBooks credentials are missing');
        }

        $data = json_decode($this->disk->get($this->filePath), true);

        return empty($key) ? $data : ($data[$key] ?? null);
    }
}
