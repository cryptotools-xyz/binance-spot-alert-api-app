<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class PriceReachedNotification extends Notification
{
    use Queueable;

    private $symbol;
    private $price;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($symbol, $price)
    {
        $this->symbol = $symbol;
        $this->price = $price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->content("$this->symbol is now $this->price$");
    }
}
