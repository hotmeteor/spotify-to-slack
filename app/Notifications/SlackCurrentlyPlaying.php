<?php

namespace App\Notifications;

use App\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
            ->content('Now playing:')
            ->attachment(function ($attachment) {
                $attachment->title($this->track->title, $this->track->url)
                    ->image($this->track->image)
                    ->fields([
                        'Artist' => $this->track->artist,
                        'Album' => $this->track->album,
                    ]);
            });
    }
}
