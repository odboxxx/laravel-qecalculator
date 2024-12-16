<?php

namespace Odboxxx\LaravelQecalculator\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;

use Odboxxx\LaravelQecalculator\Services\QecalculatorService;
use Odboxxx\LaravelQecalculator\Mail\QecalculatorHistoryMail;

class QecalculatorHistorySend implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $exportFormat;

    /**
     * Create a new job instance.
     */
    public function __construct(int $format = 1)
    {
        $this->exportFormat = $format;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailData = QecalculatorService::historyExport($this->exportFormat);
        $mailData['email'] = config('qecalculator.email_to');

        if ($mailData['rowsAffected'] > 0) {

            QecalculatorService::historyExportLogSet($mailData['filePath'],$mailData['rowsAffected']);

            Mail::to($mailData['email'])->send(new QecalculatorHistoryMail($mailData));

        }
    }
}
