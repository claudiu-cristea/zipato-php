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
    protected $endpoint;

    /**
     * @var string
     */
    protected $mail;

    /**
     * @var string
     */
    protected $sha;

    /**
     * @var string
     */
    protected $serialNumber;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @param string $endpoint
     * @param HttpClientInterface|NULL $httpClient
     */
    public function __construct(string $endpoint = null, HttpClientInterface $httpClient = null)
    {
        if (!$endpoint)
        {
            $endpoint = 'https://my.zipato.com/zipato-web/v2';
        }
        if (!$httpClient)
        {
            $httpClient = HttpClient::create();
        }
        $this->endpoint = $endpoint;
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function setMail(string $mail): ZipatoSessionInterface
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSha(string $sha): ZipatoSessionInterface
    {
        $this->sha = $sha;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSerialNumber(string $serialNumber): ZipatoSessionInterface
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function login(): void
    {
        if (empty($this->mail))
        {
            throw new \Exception('The mail is not set.');
        }
        if (empty($this->sha))
        {
            throw new \Exception('The SHA is not set.');
        }
        if (empty($this->serialNumber))
        {
            throw new \Exception('The serial number is not set.');
        }

        $response = $this->get('/user/init');
        $this->sessionId = $response->jsessionid;
        $token = sha1($response->nonce . $this->sha);

        $response = $this->get('/user/login', [
            'username' => $this->mail,
            'token' => $token,
            'serial' => $this->serialNumber,
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
        return $this->endpoint . $path . $queryString;
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
