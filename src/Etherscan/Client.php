<?php

namespace NftPortfolioTracker\Etherscan;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as HttpClient;
use JsonException;
use NftPortfolioTracker\Etherscan\Exception\EtherscanApiRequestFailed;
use Psr\Log\LoggerInterface;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class Client
{
    private LoggerInterface $logger;
    private HttpClient $httpClient;
    private string $baseUrl;
    private string $apiToken;

    private static array $transactionHashes = [];

    public function __construct(LoggerInterface $logger, string $baseUrl, string $apiToken)
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perSecond(3));

        $this->httpClient = new HttpClient([
            'handler' => $stack,
        ]);

        $this->logger = $logger;
        $this->baseUrl = $baseUrl;
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $account
     * @param string[] $contracts
     * @param int|null $latestBlockNumber
     *
     * @return array
     *
     * @throws EtherscanApiRequestFailed
     */
    public function getErc721Transactions(string $account, array $contracts, ?int $latestBlockNumber): iterable
    {
        $queryParameters = [
            'module' => 'account',
            'action' => 'tokennfttx',
            'apikey' => $this->apiToken,
            'sort' => 'desc',
            'address' => $account,
        ];

        if ($latestBlockNumber !== null) {
            $queryParameters['startblock'] = $latestBlockNumber;
        }

        foreach ($contracts as $contract) {
            $queryParameters['contractaddress'] = $contract['contract'];

            $response = $this->httpClient->request(Request::METHOD_GET, $this->baseUrl, ['query' => $queryParameters]);

            try {
                $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

                $this->validateApiResponse($responseData);

                foreach ($responseData['result'] as $transaction) {
                    yield $transaction;
                }
            } catch (EtherscanApiRequestFailed $etherscanApiRequestFailed) {
                $this->logger->warning('Empty request ' . $etherscanApiRequestFailed->getMessage());
                yield [];
            } catch (JsonException $jsonException) {
                throw new EtherscanApiRequestFailed($jsonException->getMessage() . $response->getBody());
            } catch (Throwable $throwable) {
                throw new EtherscanApiRequestFailed($throwable->getMessage());
            }
        }

        return [];
    }

    /**
     * @throws EtherscanApiRequestFailed
     */
    private function validateApiResponse(array $apiResponse): void
    {
        if (array_key_exists('status', $apiResponse) && (int) $apiResponse['status'] !== 1) {
            throw new EtherscanApiRequestFailed($apiResponse['message']);
        }

        if (array_key_exists('result', $apiResponse) && count($apiResponse['result']) === 0) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @throws EtherscanApiRequestFailed
     */
    public function getValueForTransaction(string $account, string $transactionHash, int $transactionBlockNumber, bool $isInternal): int
    {
        if (isset(self::$transactionHashes[$transactionHash])) {
            return self::$transactionHashes[$transactionHash];
        }

        $queryParameters = [
            'module' => 'account',
            'action' => $isInternal ? 'txlistinternal' : 'txlist',
            'apikey' => $this->apiToken,
            'sort' => 'desc',
            'address' => $account,
        ];

        $queryParameters['startblock'] = $transactionBlockNumber;
        $queryParameters['endblock'] = $transactionBlockNumber;

        $this->logger->info('Requesting...' , $queryParameters);
        $response = $this->httpClient->request(Request::METHOD_GET, $this->baseUrl, ['query' => $queryParameters]);

        try {
            $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $this->validateApiResponse($responseData);

            foreach ($responseData['result'] as $transaction) {
                if ($transaction['hash'] === $transactionHash) {
                    self::$transactionHashes[$transactionHash] = $transaction['value'];

                    return $transaction['value'];
                }
            }
        } catch (EtherscanApiRequestFailed $etherscanApiRequestFailed) {
            // No result, try using erc20 transfers to get value, because of weth
            $queryParameters['action'] = 'tokentx';
            $this->logger->info('Requesting...' , $queryParameters);
            $response = $this->httpClient->request(Request::METHOD_GET, $this->baseUrl, ['query' => $queryParameters]);
            $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $this->validateApiResponse($responseData);

            foreach ($responseData['result'] as $transaction) {
                if ($transaction['hash'] === $transactionHash) {
                    self::$transactionHashes[$transactionHash] = $transaction['value'];

                    return $transaction['value'];
                }
            }

            throw new EtherscanApiRequestFailed($etherscanApiRequestFailed->getMessage() . var_export($responseData, true));
        } catch (JsonException $jsonException) {
            throw new EtherscanApiRequestFailed($jsonException->getMessage() . $response->getBody());
        } catch (Throwable $throwable) {
            throw new EtherscanApiRequestFailed($throwable->getMessage() . var_export($responseData, true));
        }

        throw new EtherscanApiRequestFailed('No value found for transaction: ' . $transactionHash);
    }
}