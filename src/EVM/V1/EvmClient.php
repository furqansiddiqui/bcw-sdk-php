<?php
/*
 * This file is a part of "furqansiddiqui/bcw-sdk-php" package.
 *  https://github.com/furqansiddiqui/bcw-sdk-php
 *
 *  Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code or visit following link:
 *  https://github.com/furqansiddiqui/bcw-sdk-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace FurqanSiddiqui\BCW\EVM\V1;

use Charcoal\HTTP\Client\Request;
use Charcoal\HTTP\Client\Response;
use Charcoal\HTTP\Commons\HttpMethod;
use FurqanSiddiqui\BCW\EVM\V1\Response\AddressInfo;
use FurqanSiddiqui\BCW\EVM\V1\Response\Erc20Token;
use FurqanSiddiqui\BCW\EVM\V1\Response\NewAccount;
use FurqanSiddiqui\BCW\EVM\V1\Response\Transaction;
use FurqanSiddiqui\BCW\EVM\V1\Response\WalletInfo;
use FurqanSiddiqui\BCW\Exception\BcwApiException;
use FurqanSiddiqui\BCW\Models\WalletConfig;

/**
 * Class EvmClient
 * @package FurqanSiddiqui\BCW\EVM\V1
 */
class EvmClient
{
    public string $authHeaderToken = "api-token";
    public string $authHeaderHmac = "api-hmac";

    /**
     * @param string $hostname
     * @param WalletConfig $wallet
     * @param string|null $sslCaPath
     * @param int $timeout
     * @param int $connectTimeout
     */
    public function __construct(
        public WalletConfig    $wallet,
        public readonly string $hostname,
        public ?string         $sslCaPath = null,
        public int             $timeout = 3,
        public int             $connectTimeout = 3,
    )
    {
    }

    /**
     * @return WalletInfo
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function info(): WalletInfo
    {
        return new WalletInfo($this->apiCall(HttpMethod::GET, "/info", [], [], true));
    }

    /**
     * @param string $address
     * @return AddressInfo
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function accountInfo(string $address): AddressInfo
    {
        return new AddressInfo($this->apiCall(HttpMethod::GET, "/account", [
            "address" => $address,
            "timestamp" => time()
        ], ["address", "timestamp"], true));
    }

    /**
     * @param string $tag
     * @return NewAccount
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function createAccount(string $tag): NewAccount
    {
        return new NewAccount($this->apiCall(HttpMethod::POST, "/create_account", [
            "tag" => $tag,
            "timestamp" => time()
        ], ["tag", "timestamp"], true));
    }

    /**
     * @param int $chainId
     * @param string $contractAddress
     * @return Erc20Token
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function tokenContractErc20(int $chainId, string $contractAddress): Erc20Token
    {
        return new Erc20Token($this->apiCall(HttpMethod::GET, "/token_contract", [
            "chainId" => $chainId,
            "token" => "erc20",
            "address" => $contractAddress
        ], [], true));
    }

    /**
     * @param int $chainId
     * @param string $txnHash
     * @return Transaction
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function transaction(int $chainId, string $txnHash): Transaction
    {
        return new Transaction($this->apiCall(HttpMethod::GET, "/transaction", [
            "chainId" => $chainId,
            "hash" => $txnHash
        ], [], true)->payload->getUnsafe("transaction"));
    }

    /**
     * @param HttpMethod $method
     * @param string $endpoint
     * @param array $params
     * @param array $hmacParams
     * @param bool $defaultChecks
     * @return Response
     * @throws BcwApiException
     * @throws \Charcoal\HTTP\Client\Exception\RequestException
     * @throws \Charcoal\HTTP\Client\Exception\ResponseException
     */
    public function apiCall(
        HttpMethod $method,
        string     $endpoint,
        array      $params,
        array      $hmacParams = [],
        bool       $defaultChecks = true
    ): Response
    {
        $request = new Request($method, $this->hostname . $this->wallet->walletId . "/" . trim($endpoint, "/"));
        $request->setTimeouts($this->timeout, $this->connectTimeout);
        if ($this->sslCaPath) {
            $request->ssl()->verify(true)->ca($this->sslCaPath);
        } else {
            $request->ssl()->verify(false);
        }

        $authHeader[] = $this->authHeaderToken . " " . $this->wallet->apiKey;
        foreach ($params as $key => $value) {
            $request->payload->set($key, $value);
        }

        if ($hmacParams) {
            $hmacPayload = [];
            foreach ($hmacParams as $hmacParam) {
                if (!isset($params[$hmacParam])) {
                    throw new \RuntimeException(sprintf('Missing HMAC param "%s" value', $hmacParam));
                }

                $hmacPayload[$hmacParam] = $params[$hmacParam];
            }

            $queryString = http_build_query($hmacPayload, "", "&", PHP_QUERY_RFC3986);
            $authHeader[] = $this->authHeaderHmac . " " . hash_hmac("sha512", $queryString, $this->wallet->hmacSecret, false);
        }

        $request->headers->set("Authorization", implode(",", $authHeader));
        $apiCall = $request->send();

        if ($defaultChecks) {
            $errorObj = $apiCall->payload->getUnsafe("error");
            if (is_array($errorObj) && isset($errorObj["message"]) && is_string($errorObj["message"])) {
                throw new BcwApiException($errorObj["message"], $errorObj["code"] ?? 0);
            }

            if ($apiCall->payload->getUnsafe("status") !== true) {
                throw new BcwApiException("API result status not successful");
            }
        }

        return $apiCall;
    }
}
