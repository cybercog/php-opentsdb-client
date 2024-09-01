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

namespace Test\Integration\Cog\OpenTsdbClient;

use Cog\OpenTsdbClient\DataPoint;
use Cog\OpenTsdbClient\OpenTsdbClient;
use Http\Adapter\Guzzle7\Client;
use PHPUnit\Framework\TestCase;

final class OpenTsdbClientTest extends TestCase
{
    public function test(): void
    {
        $dataPointList[] = new DataPoint(
            metric: 'temperature',
            timestamp: time(),
            value: -38.04,
            tags: ['place' => 'south_pole'],
        );
        $dataPointList[] = new DataPoint(
            metric: 'temperature',
            timestamp: time(),
            value: -2.12,
            tags: ['place' => 'north_pole'],
        );

        $openTsdbClient = new OpenTsdbClient(
            httpClient: Client::createWithConfig(
                [
                    'timeout' => 4,
                    'connect_timeout' => 2,
                    'http_errors' => false,
                ],
            ),
            baseUri: 'http://opentsdb:4242',
        );

        $openTsdbClient->sendDataPointList($dataPointList);
    }
}
