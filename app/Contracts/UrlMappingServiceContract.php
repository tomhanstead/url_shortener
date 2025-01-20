<?php

namespace App\Contracts;

interface UrlMappingServiceContract
{
    public function mapUrl(string $originalUrl);

    public function retrieveShortUrl(string $shortKey);
}
