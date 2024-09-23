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

use Charcoal\HTTP\Client\Response;
use FurqanSiddiqui\BCW\Exception\BcwApiException;

/**
 * Class Erc20Token
 * @package FurqanSiddiqui\BCW\EVM\V1\Response
 */
readonly class Erc20Token
{
    public string $deployedAt;
    public string $name;
    public string $symbol;
    public int $decimals;
    public ?string $totalSupply;

    /**
     * @param Response $response
     * @throws BcwApiException
     */
    public function __construct(Response $response)
    {
        $token = $response->payload->getUnsafe("token");
        if (!is_array($token) || !isset($token["deployedAt"], $token["name"], $token["symbol"], $token["decimals"])) {
            throw new BcwApiException("Incomplete response for Erc20Token");
        }

        $this->deployedAt = $token["deployedAt"];
        $this->name = $token["name"];
        $this->symbol = $token["symbol"];
        $this->decimals = $token["decimals"];
        $this->totalSupply = $token["totalSupply"] ?? null;
    }
}
