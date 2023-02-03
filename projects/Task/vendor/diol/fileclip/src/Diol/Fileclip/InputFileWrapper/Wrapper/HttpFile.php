<?php

namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\InputFileWrapper\Wrapper\Exception\ImpossibleToGetFileContent;
use GuzzleHttp\Client as GuzzleHttpClient;

class HttpFile implements IWrapper
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var GuzzleHttpClient
     */
    private $httpClient;

    public function __construct($url)
    {
        $this->url = url_to_punycode($url);
        $this->httpClient = app('fileclip::http_client');
    }

    /**
     * Check if the file is valid
     * @return bool
     */
    public function isValid()
    {
        $response = $this->httpClient->head($this->url);

        return 200 === $response->getStatusCode();
    }

    /**
     * Save the file to directory with name
     * @param $dir - path directory
     * @param $fileName - name
     */
    public function save($dir, $fileName)
    {
        $newFilePath = $dir . '/' . $fileName;

        $response = $this->httpClient->get($this->url);

        if (200 === $response->getStatusCode()) {
            \File::put($newFilePath, $response->getBody()->getContents());

        } else {
            throw new ImpossibleToGetFileContent(
                $this->url,
                $response->getStatusCode() . ' ' . $response->getReasonPhrase()
            );
        }
    }

    /**
     * Get file extension
     * @return string
     */
    public function getExtension()
    {
        $response = $this->httpClient->head($this->url);

        if (200 === $response->getStatusCode()) {
            $contentType = $response->getHeader('content-type');

            $extension = mime2ext($contentType);
        }


        return isset($extension) && false !== $extension ? $extension : 'unknown';
    }

    /**
     * Get original file name
     *
     * @param bool $withExtension
     * @return string
     */
    public function getName($withExtension = true)
    {
        $name = '';

        $parsed = parse_url($this->url);
        if (!empty($parsed['path'])) {
            $name = basename($parsed['path']);

            if (!$withExtension) {
                $pointPosition = mb_strrpos($name, '.');
                $extension = false !== $pointPosition ? mb_substr($name, -(mb_strlen($name) - $pointPosition - 1)) : '';

                $suffix = $extension ? ".{$extension}" : null;
                $name = basename($parsed['path'], $suffix);
            }
        }

        return urldecode($name);
    }
}
