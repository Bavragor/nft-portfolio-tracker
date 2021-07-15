<?php

namespace NftPortfolioTracker\Etherscan;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use JsonException;
use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Etherscan\Exception\EtherscanApiRequestFailed;
use NftPortfolioTracker\Repository\ProjectRepository;
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
    private string $apiToken;
    private ProjectRepository $projectRepository;

    private static array $transactionHashes = [];

    public function __construct(LoggerInterface $logger, ProjectRepository $projectRepository, string $apiToken)
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perSecond(3));

        $this->httpClient = new HttpClient([
            'handler' => $stack,
        ]);

        $this->logger = $logger;
        $this->projectRepository = $projectRepository;
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $account
     * @param Project[] $projects
     * @param int|null $latestBlockNumber
     *
     * @return array
     *
     * @throws EtherscanApiRequestFailed
     */
    public function getErc721Transactions(string $account, array $projects, ?int $latestBlockNumber): iterable
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

        foreach ($projects as $project) {
            $baseUrl = $project->getEtherscanUrl();
            $queryParameters['contractaddress'] = $project->getContract();

            $response = $this->httpClient->request(Request::METHOD_GET, $baseUrl, ['query' => $queryParameters]);

            try {
                $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

                $this->validateApiResponse($responseData);

                foreach ($responseData['result'] as $transaction) {
                    $transaction['apiUrl'] = $baseUrl;
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

    public function getTransactionsForOpenSea(string $address, ?int $latestBlockNumber, int $direction): iterable
    {
        $openSeaProject = $this->projectRepository->findOneBy(['tokenName' => 'OpenSea']);

        $queryParameters = [
            'module' => 'account',
            'action' => $direction === TransactionDirectionEnum::IN ? 'txlist' : 'txlistinternal',
            'apikey' => $this->apiToken,
            'sort' => 'desc',
            'address' => $address,
        ];

        if ($latestBlockNumber !== null) {
            $queryParameters['startblock'] = $latestBlockNumber;
        }

        $baseUrl = $openSeaProject->getEtherscanUrl();

        $response = $this->httpClient->request(Request::METHOD_GET, $baseUrl, ['query' => $queryParameters]);

        try {
            $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $this->validateApiResponse($responseData);

            foreach ($responseData['result'] as $transaction) {
                if ($direction === TransactionDirectionEnum::IN && $transaction['to'] !== $openSeaProject->getContract()) {
                    continue;
                }

                if ($direction === TransactionDirectionEnum::OUT && $transaction['from'] !== $openSeaProject->getContract()) {
                    continue;
                }

                if ($direction === TransactionDirectionEnum::IN) {
                    $transaction['from'] = $openSeaProject->getContract();
                    $transaction['to'] = $address;
                }

                if ($direction === TransactionDirectionEnum::OUT) {
                    $transaction['gasPrice'] = 0;
                }

                if ((int) $transaction['isError'] === 1) {
                    $transaction['value'] = 0;
                }

                $transaction['tokenSymbol'] = 'OS';
                $transaction['tokenID'] = '0';

                $transaction['apiUrl'] = $baseUrl;
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
    public function getValueForTransaction(string $baseUrl, string $account, string $transactionHash, int $transactionBlockNumber, bool $isInternal): int
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

        $this->logger->info('Requesting...', $queryParameters);
        $response = $this->httpClient->request(Request::METHOD_GET, $baseUrl, ['query' => $queryParameters]);

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
            $queryParameters['sort'] = 'asc';
            $this->logger->info('Requesting...', $queryParameters);
            $response = $this->httpClient->request(Request::METHOD_GET, $baseUrl, ['query' => $queryParameters]);
            $responseData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $this->validateApiResponse($responseData);

            foreach ($responseData['result'] as $transaction) {
                if ($transaction['hash'] === $transactionHash) {
                    self::$transactionHashes[$transactionHash] = $transaction['value'];

                    return (int) $transaction['value'];
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
