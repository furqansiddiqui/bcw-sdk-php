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

namespace FurqanSiddiqui\BCW;

/**
 * Class Bcw
 * @package FurqanSiddiqui\BCW
 */
class Bcw
{
    /**
     * @param mixed $input
     * @return bool
     */
    public static function isValidWalletId(mixed $input): bool
    {
        return is_string($input) && strlen($input) === 24 && preg_match('/^[a-f0-9]{6}-[a-f0-9]{10}-[a-f0-9]{6}$/i', $input);
    }

    /**
     * @param mixed $input
     * @return bool
     */
    public static function isValidApiToken(mixed $input): bool
    {
        return is_string($input) && strlen($input) === 40 && preg_match("/^[a-f0-9]{40}$/i", $input);
    }

    /**
     * @param mixed $input
     * @return bool
     */
    public static function isValidAccountTag(mixed $input): bool
    {
        return !is_string($input) || !$input || strlen($input) > 16 || !preg_match('/^[\w\-@:]{1,16}$/i', $input);
    }
}
