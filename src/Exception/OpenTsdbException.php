<?php

/*
 * This file is part of OpenTSDB PHP Client.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\OpenTsdbClient;

final class OpenTsdbException extends \RuntimeException implements
    OpenTsdbExceptionInterface
{
}
