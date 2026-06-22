<?php

namespace App\Console\Commands;

use App\Services\DailySlotGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class GenerateDailySlots extends Command
{
    protected $signature = 'slots:generate-daily
        {--date= : Target draw date in YYYY-MM-DD format. Defaults to today in Asia/Kolkata.}
        {--source-date= : Source draw date in YYYY-MM-DD format. Defaults to the previous day.}';

    protected $description = 'Clone the previous draw date slots and slot items for the current draw date.';

    public function handle(DailySlotGenerator $generator): int
    {
        $targetDate = $this->option('date')
            ? Carbon::createFromFormat('Y-m-d', $this->option('date'), 'Asia/Kolkata')
            : now('Asia/Kolkata');

        $sourceDate = $this->option('source-date')
            ? Carbon::createFromFormat('Y-m-d', $this->option('source-date'), 'Asia/Kolkata')
            : null;

        $lockName = 'slots:generate-daily:' . $targetDate->toDateString();

        $result = Cache::lock($lockName, 300)->block(5, function () use ($generator, $targetDate, $sourceDate) {
            return $generator->generate($targetDate, $sourceDate);
        });

        if (!$result['created']) {
            $this->info(sprintf(
                'No slots created for %s. Reason: %s.',
                $result['target_date'],
                $result['reason']
            ));

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Created %d slots and %d slot items for %s from %s.',
            $result['slots_created'],
            $result['items_created'],
            $result['target_date'],
            $result['source_date']
        ));

        return self::SUCCESS;
    }
}
