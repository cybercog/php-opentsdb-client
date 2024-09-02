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

/**
 * Don't use too many tags, keep it to a fairly small number, usually up to 4 or 5 tags
 * (by default, OpenTSDB supports a maximum of 8 tags by default, which can be modified
 * by add configuration item 'tsd.storage.max_tags' in opentsdb.conf).
 */
final class DataPoint implements
    \JsonSerializable
{
    /**
     * @param int $timestamp A Unix epoch style timestamp in seconds or milliseconds.
     * @param int | float | string $value The number value to record for this data point.
     * @param array<string, string> $tags A map of tag name/tag value pairs. At least one pair must be supplied.
     */
    public function __construct(
        private string $metric,
        private int $timestamp,
        private int | float | string $value,
        private array $tags,
    ) {
        if ($this->metric === '') {
            throw new \AssertionError(
                'Metric name is empty',
            );
        }
        if ($this->timestamp <= 0) {
            throw new \AssertionError(
                "Invalid timestamp, must be greater than 0, got `$this->timestamp`",
            );
        }
        if ($this->value === '') {
            throw new \AssertionError(
                'Metric value is empty',
            );
        }
        if (!\is_numeric($this->value)) {
            throw new \AssertionError(
                "Metric value is not numeric, got `$this->value`",
            );
        }
        if ($this->tags === []) {
            throw new \AssertionError(
                'At least one tag value pair must be supplied',
            );
        }
        foreach ($this->tags as $tagName => $tagValue) {
            if (empty($tagName) || empty($tagValue)) {
                throw new \AssertionError(
                    "Tag name and value are required, got `$tagName=$tagValue`",
                );
            }
            if (!\is_string($tagName)) {
                throw new \AssertionError(
                    "Tag name must be string, got `" . get_debug_type($tagName) . "`",
                );
            }
            if (!\is_string($tagValue)) {
                throw new \AssertionError(
                    "Tag value must be string, got `" . get_debug_type($tagValue) . "`",
                );
            }
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'metric' => $this->sanitize($this->metric),
            'timestamp' => $this->timestamp,
            'value' => $this->value,
            'tags' => $this->sanitizeTags($this->tags),
        ];
    }

    /**
     * Sanitizes a metric name, tag key, or tag value by removing characters not allowed by OpenTSDB.
     *
     * Supported characters are: a-z A-Z 0-9 - _ . /
     * Unsupported characters are replaced with `-` character.
     */
    private function sanitize(
        string $value,
    ): string {
        return preg_replace(
            '#[^a-zA-Z0-9\-\_\.\/]#',
            '-',
            $value,
        );
    }

    private function sanitizeTags(
        array $tags,
    ): array {
        $result = [];

        foreach ($tags as $tagName => $tagValue) {
            $result[$this->sanitize($tagName)] = $this->sanitize($tagValue);
        }

        return $result;
    }
}
