<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Services\PunkApiService;
use App\Http\Requests\BeerRequest;
use Maatwebsite\Excel\Facades\Excel;

class BeerController extends Controller
{
    public function index(BeerRequest $request, PunkApiService $service)
    {
        return $service->getBeers(...$request->validated());
    }

    public function export(BeerRequest $request, PunkApiService $service)
    {
        $beers = $service->getBeers(...$request->validated());

        $filteredBeers = collect($beers)->map(function($value, $key){
            return collect($value)
            ->only('name', 'tagline', 'first_brewed', 'description')
            ->toArray();
        })->toArray();

        Excel::store(
            new BeerExport($filteredBeers), 
            'olw-report.xls',
            's3'
        );

        return 'Relatório criado';
    }
}
