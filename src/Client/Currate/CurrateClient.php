<?php

declare(strict_types=1);

namespace App\Client\Currate;

use App\Exception\ClientException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class CurrateClient
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(param: 'client.currate.url')]
        private string $url,
        #[Autowire(param: 'client.currate.token')]
        private string $token,
    ) {
    }

    /**
     * @return string[]
     */
    public function getCurrencyList(): array
    {
        try {
            $response = $this->client->request('GET', $this->url . '/api/?get=currency_list&key=' . $this->token);
            $statusCode = $response->getStatusCode();

            /** @var array{status: string, message: string, data: string[]} $content */
            $content = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        if ($statusCode !== 200) {
            throw new ClientException('Currency list error', $statusCode);
        }
        if ($content['status'] !== '200') {
            throw new ClientException($content['message'], (int) $content['status']);
        }

        return $content['data'];
    }

    /**
     * @param string[] $pairs
     * @return array{string: string}
     */
    public function getRates(array $pairs): array
    {
        $pairsStr = implode(',', $pairs);

        try {
            $response = $this->client->request('GET', "$this->url/api/?get=rates&pairs=$pairsStr&key=$this->token");
            $statusCode = $response->getStatusCode();

            /** @var array{status: string, message: string, data: array{string: string}} $content */
            $content = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        if ($statusCode !== 200) {
            throw new ClientException('Currency list error', $statusCode);
        }
        if ((int) $content['status'] !== 200) {
            throw new ClientException($content['message'], (int) $content['status']);
        }

        return $content['data'];
    }
}
