# OpenTSDB PHP Client

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

$openTsdbClient = new OpenTsdbClient(
    $httpClient,
    $openTsdbBaseUri,
);

$metricName = 'test_metric';
$metricUnixTime = time();
$metricValue = 42;
$metricTags = ['tag1_name' => 'tag1_value'];

$dataPointList = new DataPoint(
    $metricName,
    $metricUnixTime,
    $metricValue,
    $metricTags,
);

$this->openTsdbClient->sendDataPoints([$dataPointList]);
```

## Alternatives

- [Java](https://github.com/sps/metrics-opentsdb)
- [Go](https://github.com/bluebreezecf/opentsdb-goclient)
- [.net](https://github.com/dejanfajfar/openTSDB.net)
