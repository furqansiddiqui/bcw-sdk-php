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
 * Class TokenTransfer
 * @package FurqanSiddiqui\BCW\EVM\V1\Response
 */
readonly class TokenTransfer
{
    public string $from;
    public string $to;
    public string $token;
    public string $tokenType;
    public string $amount;
    public int $logIndex;

    /**
     * @param array $transfer
     * @param int $index
     * @throws BcwApiException
     */
    public function __construct(array $transfer, int $index)
    {
        if (!isset($transfer["from"], $transfer["to"], $transfer["token"], $transfer["tokenType"],
            $transfer["amount"], $transfer["logIndex"])) {
            throw new BcwApiException("Incomplete TokenTransfer object at index " . $index);
        }

        $this->from = $transfer["from"];
        $this->to = $transfer["to"];
        $this->token = $transfer["token"];
        $this->tokenType = $transfer["tokenType"];
        $this->amount = $transfer["amount"];
        $this->logIndex = $transfer["logIndex"];
    }
}
