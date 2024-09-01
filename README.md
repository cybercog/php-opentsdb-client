# OpenTSDB PHP Client

<p align="center">
<a href="https://discord.gg/A8Phy8yJH6"><img src="https://img.shields.io/static/v1?logo=discord&label=&message=Discord&color=36393f&style=flat-square" alt="Discord"></a>
<a href="https://github.com/cybercog/php-open-tsdb-client/releases"><img src="https://img.shields.io/github/release/cybercog/php-open-tsdb-client.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/php-open-tsdb-client/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/php-open-tsdb-client/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://github.com/cybercog/php-open-tsdb-client/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/php-open-tsdb-client.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

This package allows you to send metrics (data points) to the OpenTSDB database
from the PHP application using an HTTP API.

- [OpenTSDB API](http://opentsdb.net/docs/build/html/api_http/index.html)

## What is OpenTSDB

OpenTSDB is a distributed, scalable Time Series Database (TSDB) written on top of HBase.
OpenTSDB was written to address a common need: store, index and serve metrics collected
from computer systems (network gear, operating systems, applications) at a large scale,
and make this data easily accessible and graphable.

OpenTSDB provides an HTTP based application programming interface to enable
integration with external systems. Almost all OpenTSDB features are accessible
via the API such as querying time-series data, managing metadata and storing data points.

## Usage

```php
$httpClient = \Http\Adapter\Guzzle7\Client::createWithConfig(
    [
        'timeout' => 4,
        'connect_timeout' => 2,
        'http_errors' => false,
    ],
);
$openTsdbBaseUri = 'http://localhost:4242';

$openTsdbClient = new \Cog\OpenTsdbClient\OpenTsdbClient(
    $httpClient,
    $openTsdbBaseUri,
);

$dataPointList[] = new \Cog\OpenTsdbClient\DataPoint(
    metric: 'temperature',
    timestamp: time(),
    value: -38.04,
    tags: ['place' => 'south_pole'],
);
$dataPointList[] = new \Cog\OpenTsdbClient\DataPoint(
    metric: 'temperature',
    timestamp: time(),
    value: -2.12,
    tags: ['place' => 'north_pole'],
);

$this->openTsdbClient->sendDataPoints($dataPointList);
```

## Alternatives

- [Java](https://github.com/sps/metrics-opentsdb)
- [Go](https://github.com/bluebreezecf/opentsdb-goclient)
- [.net](https://github.com/dejanfajfar/openTSDB.net)
