<?php

declare(strict_types=1);

namespace Zipato;

interface ZipatoSessionInterface
{
    /**
     * @param string $mail
     *
     * @return $this
     */
    public function setMail(string $mail): self;

    /**
     * @param string $sha
     *
     * @return $this
     */
    public function setSha(string $sha): self;

    /**
     * @param string $serialNumber
     *
     * @return $this
     */
    public function setSerialNumber(string $serialNumber): self;

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
     * @param array $queryArray
     * @param array $bodyArray
     * @param string|null $uuid
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function put(string $path, array $queryArray = [], array $bodyArray = [], string $uuid = null): void;

    /**
     * @param string $path
     * @param array $queryArray
     * @param array $bodyArray
     * @param string|null $uuid
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function post(string $path, array $queryArray = [], array $bodyArray = [], string $uuid = null);

    /**
     * @param string $path
     * @param array $queryArray
     * @param string|null $uuid
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function delete(string $path, array $queryArray = [], string $uuid = null);
}
