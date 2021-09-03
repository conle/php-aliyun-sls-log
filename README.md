# PHP SDK FOR ALI SLS LOG

## Environment Requirement

1. PHP 7.1.7 and later

## API VERSION

0.6.1

## SDK RELEASE TIME

2018-02-18

## Introduction

API Reference: [中文](https://help.aliyun.com/document_detail/29007.html) [ENGLISH](https://www.alibabacloud.com/help/doc-detail/29007.htm)

### Install

composer require "conle/php-aliyun-sls-log"

### Use

```
<?php
use AliSlsLog\SaveLogSls;

$config = [
  "end_point" => "http://cn-shanghai.log.aliyuncs.com"
  "project" => ""
  "access_key" => ""
  "access_key_secret" => ""
];
$client = new SaveLogSls($config, $logstore);

$content = [];
$topic = '';
$client->putLogs($content,$topic);

```

