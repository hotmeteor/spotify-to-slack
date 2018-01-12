<?php


namespace App;

use stdClass;

class SpotifyTrack
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return data_get($this->data, 'item.id');
    }

    public function getAlbum(): stdClass
    {
        return data_get($this->data, 'item.album');
    }

    public function getArtist(): stdClass
    {
        return array_first(data_get($this->data, 'item.artists'));
    }

    public function getTrackName()
    {
        return data_get($this->data, 'item.name');
    }

    public function getUrl()
    {
        return data_get($this->data, 'item.href');
    }

    public function getIsPlaying(): bool
    {
        return data_get($this->data, 'is_playing');
    }

    public function getDurationMs(): ?int
    {
        return data_get($this->data, 'item.duration_ms');
    }
}
