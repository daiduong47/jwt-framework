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

namespace Jose\Component\Encryption\Algorithm\ContentEncryption;

use Base64Url\Base64Url;
use Jose\Component\Encryption\Algorithm\ContentEncryptionAlgorithm;

abstract class AESGCM implements ContentEncryptionAlgorithm
{
    public function allowedKeyTypes(): array
    {
        return []; //Irrelevant
    }

    public function encryptContent(string $data, string $cek, string $iv, ?string $aad, string $encoded_protected_header, ?string &$tag = null): string
    {
        $calculated_aad = $encoded_protected_header;
        if (null !== $aad) {
            $calculated_aad .= '.'.Base64Url::encode($aad);
        }
        $tag = '';
        $C = \openssl_encrypt($data, $this->getMode(), $cek, OPENSSL_RAW_DATA, $iv, $tag, $calculated_aad);
        if (false === $C) {
            throw new \InvalidArgumentException('Unable to encrypt the data.');
        }

        return $C;
    }

    public function decryptContent(string $data, string $cek, string $iv, ?string $aad, string $encoded_protected_header, string $tag): string
    {
        $calculated_aad = $encoded_protected_header;
        if (null !== $aad) {
            $calculated_aad .= '.'.Base64Url::encode($aad);
        }

        $P = \openssl_decrypt($data, $this->getMode(), $cek, OPENSSL_RAW_DATA, $iv, $tag, $calculated_aad);
        if (false === $P) {
            throw new \InvalidArgumentException('Unable to decrypt or to verify the tag.');
        }

        return $P;
    }

    public function getIVSize(): int
    {
        return 96;
    }

    abstract protected function getMode(): string;
}
