<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Kromacie\IntrospectionMiddleware\Repositories\AccessTokenRepositoryInterface;
use function json_decode;

class AuthServer
{
    protected array $config;

    protected AccessTokenRepositoryInterface $accessTokenRepository;

    public function __construct(array $config, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->config = $config;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @param string $token
     * @return Client
     * @throws GuzzleException
     */
    public function introspect(string $token): Client
    {
        $client = $this->createHttpClient();

        $response = $client->post($this->config['introspect_token'], [
            RequestOptions::BODY => [
                'token' => $token
            ],
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ]
        ]);

        $options = json_decode($response->getBody()->getContents(), true);

        if (!$options) {
            throw new InvalidArgumentException('Malformed response body.');
        }

        return new Client($options);
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    protected function getAccessToken(): string
    {
        return tap($this->accessTokenRepository->find($this->createAccessTokenIdentifier()), function (?string $accessToken) {
            if (!$accessToken) {
                $accessToken = $this->authorize();

                $this->accessTokenRepository->save($this->createAccessTokenIdentifier(), $accessToken);
            }

            return $accessToken;
        });
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    protected function authorize(): string
    {
        $client = $this->createHttpClient();

        $response = $client->post($this->config['access_token_url'], [
            RequestOptions::BODY => [
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'grant_type' => 'client_credentials'
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        if (strpos((string) $response->getStatusCode(), '20') !== 0) {
            throw new InvalidArgumentException('Request could not been authorized.');
        }

        $accessToken = json_decode($response->getBody()->getContents(), true)['access_token'] ?? null;

        if (!$accessToken) {
            throw new InvalidArgumentException('Malformed response body');
        }

        return $accessToken;
    }

    protected function createAccessTokenIdentifier(): string
    {
        return 'access_token_' . $this->config['server_identifier'];
    }

    protected function createHttpClient(): HttpClient
    {
        return new HttpClient();
    }
}
