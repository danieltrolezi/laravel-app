<?php

namespace App\Services;

class RawgDomainService extends RawgBaseService
{
    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->call(uri: 'genres')['results'];
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->call(uri: 'tags')['results'];
    }

    /**
     * @return array
     */
    public function getPlatforms(): array
    {
        return $this->call(uri: 'platforms')['results'];
    }
}
