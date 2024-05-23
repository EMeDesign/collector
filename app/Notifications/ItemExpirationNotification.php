<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Item $item)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url('/items');

        return (new MailMessage())
            ->subject('物品过期提醒')
            ->greeting(trim('尊敬的用户 ' . $this->item->creator->name . ' ，您好！'))
            ->line(trim('您有一个物品将于' . $this->item->expired_at->diffForHumans() . '过期：'.$this->item->name))
            ->action('点此查看您的物品列表', $url)
            ->line('来自 Collector 团队！');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
