<?php

declare(strict_types=1);

namespace Zipato;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZipatoSession implements ZipatoSessionInterface
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://my.zipato.com/zipato-web/v2';

    /**
     * @var string
     */
    protected $mail;

    /**
     * @var string
     */
    protected $passHash;

    /**
     * @var string
     */
    protected $serial;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @param string $mail
     * @param string $passHash
     * @param string $serial
     * @param HttpClientInterface|NULL $httpClient
     */
    public function __construct(string $mail, string $passHash, string $serial, HttpClientInterface $httpClient = NULL)
    {
        $this->mail = $mail;
        $this->passHash = $passHash;
        $this->serial = $serial;
        if (!$httpClient) {
            $httpClient = HttpClient::create();
        }
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function login(): void
    {
        $response = $this->get('/user/init');
        $this->sessionId = $response->jsessionid;
        $token = sha1($response->nonce . $this->passHash);

        $response = $this->get('/user/login', [
            'username' => $this->mail,
            'token' => $token,
        ]);

        if (empty($response->success)) {
            throw new \Exception("Login failed.");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function logout(): void
    {
        $this->get('/user/logout');
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, array $queryArray = [], string $uuid = null)
    {
        $options = [
            'headers' => $this->getHeaders(),
        ];
        $response = $this->httpClient->request('GET', $this->buildUrl($path, $uuid, $queryArray), $options);

        return json_decode($response->getContent());
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $path, array $bodyArray = [], string $uuid = null): void
    {
        $body = json_encode($bodyArray);
        $headers = [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($body),

            ] + $this->getHeaders();
        $options = [
            'headers' => $headers,
            'body' => $body,
        ];
        $this->httpClient->request('PUT', $this->buildUrl($path, $uuid), $options);
    }

    /**
     * @param string $path
     * @param string|null $uuid
     * @param array $queryArray
     * @return string
     */
    protected function buildUrl(string $path, string $uuid = null, array $queryArray = []): string
    {
        $path = str_replace('{uuid}', $uuid, $path);
        $queryString = '';
        if ($queryArray) {
            $queryString = '?' . http_build_query($queryArray);
        }
        return static::ENDPOINT . $path . $queryString;
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        $headers = [];
        if ($this->sessionId) {
            $headers['cookie'] = 'JSESSIONID=' . $this->sessionId;
        }
        return $headers;
    }
}
