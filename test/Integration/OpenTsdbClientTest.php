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

namespace Test\Integration\Cog\OpenTsdbClient;

use Cog\OpenTsdbClient\DataPoint;
use Cog\OpenTsdbClient\OpenTsdbClient;
use Http\Adapter\Guzzle7\Client;
use PHPUnit\Framework\TestCase;

final class OpenTsdbClientTest extends TestCase
{
    public function testSuccess(): void
    {
        if ($_ENV['APP_ENV'] === 'ci') {
            $this->markTestSkipped('Only for development environment. Need to pull opentsdb container to GitHub.');
        }

        $dataPointList[] = new DataPoint(
            metric: 'temperature',
            timestamp: 1,
            value: -38.04,
            tags: ['place' => 'south_pole'],
        );
        $dataPointList[] = new DataPoint(
            metric: 'temperature',
            timestamp: 1,
            value: -2.12,
            tags: ['place' => 'north_pole'],
        );

        $openTsdbClient = $this->initOpenTsdbClient();

        $response = $openTsdbClient->sendDataPointList($dataPointList);
        $this->assertSame(200, $response->httpStatusCode());
        $this->assertSame(2, $response->success());
        $this->assertSame(0, $response->failed());
        $this->assertSame([], $response->errors());
    }

    private function initOpenTsdbClient(): OpenTsdbClient
    {
        return new OpenTsdbClient(
            httpClient: Client::createWithConfig(
                [
                    'timeout' => 4,
                    'connect_timeout' => 2,
                    'http_errors' => false,
                ],
            ),
            baseUri: 'http://opentsdb:4242',
        );
    }
}
