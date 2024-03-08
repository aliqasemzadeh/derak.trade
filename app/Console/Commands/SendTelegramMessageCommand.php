<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;

class SendTelegramMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'derak:send-telegram-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        $response = $telegram->sendMessage([
            'chat_id' => 'CHAT_ID',
            'text' => 'Hello World'
        ]);

        $messageId = $response->getMessageId();
    }
}
