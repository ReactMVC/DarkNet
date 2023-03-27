<?php
/*
DarkNet

A powerful php library for internet testing
*/
namespace Darknet;

use GuzzleHttp\Client;

// class Speed
class Speed
{
    private $client;
    private $baseUrl;
    private $pingUrl = 'https://www.google.com';

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/') . '/';
        $this->client = new Client();
    }

    public function setPingUrl($url)
    {
        $this->pingUrl = $url;
    }

    public function getPing()
    {
        $start = microtime(true);
        exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg(parse_url($this->pingUrl, PHP_URL_HOST))), $res, $rval);
        $end = microtime(true);
        if ($rval == 0) {
            return round(($end - $start) * 1000, 2); // ms
        }
        return false;
    }

    public function getDownloadSpeed($scale = 'MB')
    {
        $response = $this->client->get($this->baseUrl . 'testfile.bin');
        $size = (float) $response->getHeaderLine('Content-Length');
        $time = (float) $response->getHeaderLine('X-Request-Time') / 1000; // seconds
        $speed = $size / $time;
        return $this->formatSize($speed, $scale);
    }

    public function getUploadSpeed($scale = 'MB')
    {
        $fp = fopen(tempnam(sys_get_temp_dir(), 'upload'), 'r');
        $response = $this->client->put($this->baseUrl . 'testfile.bin', [
            'body' => $fp,
            'headers' => [
                'Content-Length' => filesize($fp),
            ],
        ]);
        $size = (float) $response->getHeaderLine('Content-Length');
        $time = (float) $response->getHeaderLine('X-Request-Time') / 1000; // seconds
        $speed = $size / $time;
        return $this->formatSize($speed, $scale);
    }

    private function formatSize($size, $scale)
    {
        switch ($scale) {
            case 'GB':
                $size /= 1024;
            case 'MB':
                $size /= 1024;
            default:
                break;
        }
        return round($size, 2) . " {$scale}/s";
    }
}