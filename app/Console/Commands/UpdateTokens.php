<?php

namespace App\Console\Commands;

use App\Jobs\UpdatePriceJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'derak:update-tokens';

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
        $tokens = config('tokens.active');
        foreach ($tokens as $token) {
            Log::info($token);
            echo($token);
            UpdatePriceJob::dispatch($token);
        }
    }
}
