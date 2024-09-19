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

/**
 * Class WalletInfo
 * @package FurqanSiddiqui\BCW\EVM\V1\Response
 */
readonly class WalletInfo
{
    public string $wallet;
    public string $type;
    public string $name;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->wallet = $response->payload->getASCII("wallet");
        $this->type = $response->payload->getASCII("wallet");
        $this->name = $response->payload->getASCII("wallet");
    }
}