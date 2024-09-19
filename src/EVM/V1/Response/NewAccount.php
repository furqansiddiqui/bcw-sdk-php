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
 * Class NewAccount
 * @package FurqanSiddiqui\BCW\EVM\V1\Response
 */
readonly class NewAccount
{
    public string $address;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->address = $response->payload->getUnsafe("address");
    }
}