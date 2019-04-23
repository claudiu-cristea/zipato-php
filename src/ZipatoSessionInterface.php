<?php

declare(strict_types=1);

namespace Zipato;

interface ZipatoSessionInterface
{
    /**
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function login(): void;

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function logout(): void;

    /**
     * @param string $path
     * @param array $queryArray
     * @param string|null $uuid
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(string $path, array $queryArray = [], string $uuid = null);

    /**
     * @param string $path
     * @param array $bodyArray
     * @param string|null $uuid
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function put(string $path, array $bodyArray = [], string $uuid = null): void;
}
