# PHP OpenTSDB HTTP API Client

<p align="center">
<a href="https://discord.gg/A8Phy8yJH6"><img src="https://img.shields.io/static/v1?logo=discord&label=&message=Discord&color=36393f&style=flat-square" alt="Discord"></a>
<a href="https://github.com/cybercog/php-opentsdb-client/releases"><img src="https://img.shields.io/github/release/cybercog/php-opentsdb-client.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/php-opentsdb-client/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/php-opentsdb-client/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://github.com/cybercog/php-opentsdb-client/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/php-opentsdb-client.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

This package allows you to send (push) metrics (data points) to the OpenTSDB database
from the PHP application using an HTTP API.

- [OpenTSDB HTTP API](http://opentsdb.net/docs/build/html/api_http/index.html)

This package does not cover Telnet API, and that's why:

- Telnet API use cases: quick tests, debugging, or simple commands in a development environment.
- HTTP API use cases: production applications, complex data queries, integrations with other systems, and secure communications.

OpenTSDB HTTP API supported by:
- [VictoriaMetrics](https://docs.victoriametrics.com/#sending-opentsdb-data-via-http-apiput-requests) but because of [limitation](https://github.com/VictoriaMetrics/VictoriaMetrics/issues/959) `sendDataPointListSilently` should be used to send metrics.

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

$openTsdbClient = new \Cog\OpenTsdbClient\OpenTsdbClient(
    httpClient: \Http\Adapter\Guzzle7\Client::createWithConfig(
        [
            'timeout' => 4,
            'connect_timeout' => 2,
            'http_errors' => false,
        ],
    ),
    baseUri: 'http://opentsdb:4242',
);

$openTsdbClient->sendDataPointList($dataPointList);
```

## Alternatives

- Java: [sps/metrics-opentsdb](https://github.com/sps/metrics-opentsdb)
- Go: [bluebreezecf/opentsdb-goclient](https://github.com/bluebreezecf/opentsdb-goclient)
- .net: [dejanfajfar/openTSDB.net](https://github.com/dejanfajfar/openTSDB.net)

## License

- `PHP OpenTSDB HTTP API Client` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

## 🌟 Stargazers over time

[![Stargazers over time](https://chart.yhype.me/github/repository-star/v1/R_kgDOMqZgDA.svg)](https://yhype.me?utm_source=github&utm_medium=cybercog-php-opentsdb-client&utm_content=chart-repository-star-cumulative)

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[CyberCog]: https://cybercog.su
