<?php

namespace App\Notifications;

use App\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackCurrentlyPlaying extends Notification
{
    use Queueable;

    private $track;

    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->success()
            ->content($notifiable->name.' is listening to:')
            ->attachment(function ($attachment) {
                $attachment->title($this->track->title, $this->track->url)
                    ->image($this->track->image)
                    ->fields([
                        'Artist' => $this->track->artist,
                        'Album' => $this->track->album,
                        'Duration' => $this->formatDuration($this->track->duration_ms),
                        'Track ID' => $this->track->track_id,
                    ]);
            });
    }

    protected function formatDuration($ms = null)
    {
        if (!$ms) {
            return;
        }

        $seconds = $ms / 1000;
        $minutes = 0;

        if ($seconds > 60) {
            $minutes = floor($seconds / 60);
            $seconds = floor($seconds - ($minutes * 60));
        }

        if ($minutes > 0) {
            return $minutes.'m '.$seconds.'s';
        }

        return $seconds.'s';
    }
}
