<?php

namespace App\Jobs;

use App\Models\Analyse;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\GoogleSheets;
use Illuminate\Support\Collection;

class SendAnalysesFromGoogleSheets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $items;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GoogleSheets $gs)
    {
        // перенести в очередь
        // if check "Без категории"
        if (in_array('not_categories', $this->items['array'])) {
            $uniqueNotCategory = Analyse::getAnalysesForGoogleSheetsWithoutCategory();
        }

        $uniqueCollectionHasCategory = Analyse::getAnalysesForGoogleSheetsWithCategory($this->items);

        // если есть коллекция анализов без категорий, объединяем с коллекцией с категориями
        $mergedCollection = (isset($uniqueNotCategory))
            ? $uniqueNotCategory->merge($uniqueCollectionHasCategory)->sortBy('code')
            : $uniqueCollectionHasCategory;

        // отправляем в gs
        if ($mergedCollection->count() > 0) {
            $gs->updateTable($mergedCollection->where('deleted_at', '==', null)->sortBy('code'));
        }

        // конец переноса в очередь
    }

    public function failed()
    {
        info(__CLASS__ . ": ошибка выполнения отправки анализов в Google Sheets");
    }
}
