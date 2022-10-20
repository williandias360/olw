<?php

namespace App\Jobs;

use App\Exports\BeerExport;
use Illuminate\Bus\Queueable;
use App\Services\PunkApiService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected array $data,
        protected string $filename,
        protected PunkApiService $service = new PunkApiService()
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $beers = $this->service->getBeers(...$this->data);

        $filteredBeers = array_map(function($value){
            return collect($value)
            ->only('name', 'tagline', 'first_brewed', 'description')
            ->toArray();
        }, $beers);

        
        Excel::store(
            new BeerExport($filteredBeers), 
            $this->filename,
            's3'
        );
    }
}
