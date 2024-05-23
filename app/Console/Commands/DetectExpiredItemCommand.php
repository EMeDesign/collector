<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Notifications\ItemExpirationNotification;
use Illuminate\Console\Command;

class DetectExpiredItemCommand extends Command
{
    protected $signature = 'detect:expired-item';

    protected $description = 'Command description';

    public function handle(): void
    {
        $adventItems = Item::query()
            ->with(['creator'])
            ->where('expired_at', '>', now()->subDay())
            ->where('owner_id', 0)
            ->get();

        foreach ($adventItems as $item) {
            $item->creator->notify(new ItemExpirationNotification($item));
            $this->comment('物品 ' . $item->name . ' 的过期提醒已发送至用户 ' . $item->creator->name . ' 的邮箱');
        }

        $this->info('任务执行完毕！');
    }
}
