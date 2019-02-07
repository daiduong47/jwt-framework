<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Component\Core\Util;

final class JsonConverter
{
    public static function encode($payload): string
    {
        $result = \json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 512);
        if (false === $result) {
            throw new \InvalidArgumentException('Unable to encode the data');
        }

        return $result;
    }

    public static function decode(string $payload)
    {
        return \json_decode($payload, true, 512, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
