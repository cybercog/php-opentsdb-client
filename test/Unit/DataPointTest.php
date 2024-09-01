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

namespace Test\Unit\Cog\OpenTsdbClient;

use Cog\OpenTsdbClient\DataPoint;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DataPointTest extends TestCase
{
    /**
     * @dataProvider provideItCanSanitizeValuesData
     */
    #[DataProvider('provideItCanSanitizeValuesData')]
    public function testItCanSanitizeValues(
        string $expectedJson,
        string $metric,
        array $tags,
    ): void {
        $dataPoint = new DataPoint(
            metric: $metric,
            timestamp: 0,
            value: 1,
            tags: $tags,
        );

        $this->assertSame(
            $expectedJson,
            json_encode($dataPoint),
        );
    }

    public static function provideItCanSanitizeValuesData(): array
    {
        return [
            [
                '{"metric":"metric-name","timestamp":0,"value":1,"tags":{"tag-name":"-value"}}',
                'metric:name',
                ['tag:name' => '$value'],
            ],
        ];
    }
}
