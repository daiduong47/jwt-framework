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

namespace Jose\Bundle\JoseFramework\Services;

use Jose\Bundle\JoseFramework\Event\Events;
use Jose\Bundle\JoseFramework\Event\JWEBuiltFailureEvent;
use Jose\Bundle\JoseFramework\Event\JWEBuiltSuccessEvent;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\JWE;
use Jose\Component\Encryption\JWEBuilder as BaseJWEBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class JWEBuilder extends BaseJWEBuilder
{
    private $eventDispatcher;

    public function __construct(AlgorithmManager $keyEncryptionAlgorithmManager, AlgorithmManager $contentEncryptionAlgorithmManager, CompressionMethodManager $compressionManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($keyEncryptionAlgorithmManager, $contentEncryptionAlgorithmManager, $compressionManager);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build(): JWE
    {
        try {
            $jwe = parent::build();
            $this->eventDispatcher->dispatch(Events::JWE_BUILT_SUCCESS, new JWEBuiltSuccessEvent($jwe));

            return $jwe;
        } catch (\Throwable $throwable) {
            $this->eventDispatcher->dispatch(Events::JWE_BUILT_FAILURE, new JWEBuiltFailureEvent());

            throw $throwable;
        }
    }
}