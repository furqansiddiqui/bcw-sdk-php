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

namespace FurqanSiddiqui\BCW\EVM\V1\Response;

use FurqanSiddiqui\BCW\Exception\BcwApiException;

/**
 * Class Transaction
 * @package FurqanSiddiqui\BCW\EVM\V1\Response
 */
readonly class Transaction
{
    public string $hash;
    public bool $status;
    public ?int $confirmations;
    public ?int $blockNumber;
    public ?string $blockHash;
    public ?int $blockTxnIndex;

    public string $from;
    public ?string $to;
    public string $value;
    public string $valueWei;
    public array $transfers;

    public int $gasLimit;
    public ?string $gasPrice;
    public ?string $gasPriceWei;
    public int $nonce;
    public ?array $errors;

    /**
     * @param array $txObject
     * @throws BcwApiException
     */
    public function __construct(array $txObject)
    {
        if (!isset($txObject["hash"], $txObject["status"], $txObject["from"], $txObject["value"],
            $txObject["valueWei"], $txObject["gasLimit"], $txObject["nonce"], $txObject["transfers"])) {
            throw new BcwApiException("Incomplete Transaction object");
        }

        $this->hash = $txObject["hash"];
        $this->status = $txObject["status"];
        $this->confirmations = $txObject["confirmations"] ?? null;
        $this->blockNumber = $txObject["blockNumber"] ?? null;
        $this->blockHash = $txObject["blockHash"] ?? null;
        $this->blockTxnIndex = $txObject["blockTxnIndex"] ?? null;
        $this->from = $txObject["from"];
        $this->to = $txObject["to"] ?? null;
        $this->value = $txObject["value"];
        $this->valueWei = $txObject["valueWei"];

        $this->gasLimit = $txObject["gasLimit"];
        $this->gasPrice = $txObject["gasPrice"] ?? null;
        $this->gasPriceWei = $txObject["gasPriceWei" ?? null];
        $this->nonce = $txObject["nonce"];

        $errors = [];
        $transfers = $txObject["transfers"];
        if (!is_array($transfers) || !$transfers) {
            $this->transfers = [];
        } else {
            $tokenTransfers = [];
            $tI = -1;
            foreach ($transfers as $transfer) {
                $tI++;
                try {
                    $tokenTransfers[] = new TokenTransfer($transfer, $tI);
                } catch (BcwApiException $e) {
                    $errors[] = $e->getMessage();
                }
            }

            $this->transfers = $tokenTransfers;
        }

        $this->errors = count($errors) > 0 ? $errors : null;
    }
}


