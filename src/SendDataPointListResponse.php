<?php

/*
 * This file is part of PHP OpenTSDB HTTP API Client.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\OpenTsdbClient;

final class SendDataPointListResponse
{
    public function __construct(
        private $httpStatusCode,
        private int $success,
        private int $failed,
        private array $errors,
    ) {}

    public function httpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function success(): int
    {
        return $this->success;
    }

    public function failed(): int
    {
        return $this->failed;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
