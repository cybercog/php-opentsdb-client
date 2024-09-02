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
            timestamp: 1,
            value: 1,
            tags: $tags,
        );

        $this->assertSame(
            $expectedJson,
            json_encode($dataPoint),
        );
    }

    public function testItCannotInstantiateWithEmptyMetric(): void
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage('Metric name is empty');

        new DataPoint(
            metric: '',
            timestamp: 1,
            value: 1,
            tags: ['tag' => 'value'],
        );
    }

    /**
     * @dataProvider provideItCannotInstantiateWithInvalidTimestamp
     */
    #[DataProvider('provideItCannotInstantiateWithInvalidTimestamp')]
    public function testItCannotInstantiateWithInvalidTimestamp(
        int $timestamp,
    ): void {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage("Invalid timestamp, must be greater than 0, got `$timestamp`");

        new DataPoint(
            metric: 'test',
            timestamp: $timestamp,
            value: 1,
            tags: ['tag' => 'value'],
        );
    }

    public function testItCannotInstantiateWithEmptyValue(): void
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage('Metric value is empty');

        new DataPoint(
            metric: 'test',
            timestamp: 1,
            value: '',
            tags: ['tag' => 'value'],
        );
    }

    /**
     * @dataProvider provideItCannotInstantiateWithNotNumberValue
     */
    #[DataProvider('provideItCannotInstantiateWithNotNumberValue')]
    public function testItCannotInstantiateWithNotNumberValue(
        string $value,
    ): void {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage("Metric value is not numeric, got `$value`");

        new DataPoint(
            metric: 'test',
            timestamp: 1,
            value: $value,
            tags: ['tag' => 'value'],
        );
    }

    public function testItCannotInstantiateWithEmptyTags(): void
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage('At least one tag value pair must be supplied');

        new DataPoint(
            metric: 'test',
            timestamp: 1,
            value: 1,
            tags: [],
        );
    }

    /**
     * @dataProvider provideItCannotInstantiateWithMissingTagNameOrValue
     */
    #[DataProvider('provideItCannotInstantiateWithMissingTagNameOrValue')]
    public function testItCannotInstantiateWithMissingTagNameOrValue(
        array $tags,
    ): void {
        $tagPair = array_key_first($tags) . "=" . $tags[array_key_first($tags)];
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage(
            "Tag name and value are required, got `$tagPair`",
        );

        new DataPoint(
            metric: 'test',
            timestamp: 1,
            value: 1,
            tags: $tags,
        );
    }

    /**
     * @dataProvider provideItCannotInstantiateWithTagNameOrValueInvalidTypes
     */
    #[DataProvider('provideItCannotInstantiateWithTagNameOrValueInvalidTypes')]
    public function testItCannotInstantiateWithTagNameOrValueInvalidTypes(
        array $tags,
        string $exceptionMessage,
    ): void {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage($exceptionMessage);

        new DataPoint(
            metric: 'test',
            timestamp: 1,
            value: 1,
            tags: $tags,
        );
    }

    public static function provideItCanSanitizeValuesData(): array
    {
        return [
            [
                '{"metric":"metric-name","timestamp":1,"value":1,"tags":{"tag-name":"-value"}}',
                'metric:name',
                ['tag:name' => '$value'],
            ],
        ];
    }

    public static function provideItCannotInstantiateWithInvalidTimestamp(): array
    {
        return [
            [
                0,
            ],
        ];
    }

    public static function provideItCannotInstantiateWithNotNumberValue(): array
    {
        return [
            ['text'],
            ['0.0.0'],
            ['-inf'],
            ['+inf'],
        ];
    }

    public static function provideItCannotInstantiateWithMissingTagNameOrValue(): array
    {
        return [
            [['key' => '']],
            [['' => 'value']],
        ];
    }

    public static function provideItCannotInstantiateWithTagNameOrValueInvalidTypes(): array
    {
        return [
            [[5 => 'value'], 'Tag name must be string, got `int`'],
            [['name' => 5], 'Tag value must be string, got `int`'],
        ];
    }
}
