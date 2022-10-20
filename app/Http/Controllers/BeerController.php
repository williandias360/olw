<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Services\PunkApiService;
use App\Http\Requests\BeerRequest;
use Illuminate\Support\Facades\Auth;

class BeerController extends Controller
{
    public function index(BeerRequest $request, PunkApiService $service)
    {
        $beers = $service->getBeers(...$request->validated());
        return Inertia::render("Beers", [
            "beers" => $beers
        ]);
    }

    public function export(BeerRequest $request)
    {
        $filename = "cervejas-encontradas-" . now()->format("Y-m-d - H_i") . ".xlsx";

        ExportJob::withChain([
            new SendExportEmailJob($filename),
            new StoreExportDataJob(auth()->user(), $filename)
        ])->dispatch($request->validated(), $filename);

        return 'Relatório criado';
    }
}
