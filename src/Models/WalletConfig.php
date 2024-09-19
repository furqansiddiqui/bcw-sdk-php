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

namespace FurqanSiddiqui\BCW\Models;

/**
 * Class WalletConfig
 * @package FurqanSiddiqui\BCW\Models
 */
readonly class WalletConfig
{
    /**
     * @param string $walletId
     * @param string $apiKey
     * @param string $hmacSecret
     */
    public function __construct(
        public string $walletId,
        public string $apiKey,
        public string $hmacSecret,
    )
    {
    }
}
