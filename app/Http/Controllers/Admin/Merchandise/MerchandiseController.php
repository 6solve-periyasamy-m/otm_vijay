<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchandise\MerchandiseRequest;
use App\Models\Merchandise\Merchandise;
use App\Repository\Model\Merchandise\MerchandiseRepository;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merch = Merchandise::withCount('inventory')->get();
        return view('pages.admin.merchandise.table', ['merchandise' => $merch,]);
    }

    public function create()
    {
        return view('pages.admin.merchandise.form');
    }

    public function store(MerchandiseRequest $request)
    {
        $merchandise = MerchandiseRepository::create($request->getDataset(), $request->image);
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function show(Merchandise $merchandise)
    {
        return view('pages.admin.merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function detailed(Merchandise $merchandise)
    {
        return view('pages.admin.merchandise.view', ['merchandise' => $merchandise, 'detailed' => true]);
    }

    public function edit(Merchandise $merchandise, string $view = 'overview')
    {
        return view('pages.admin.merchandise.form', ['merchandise' => $merchandise, 'view' => $view,]);
    }

    public function update(MerchandiseRequest $request, Merchandise $merchandise, string $view = 'overview')
    {
        $merchandise->repository->updateWithImage($request->getDataset(), $request->image);
        if ($view === 'detailed') {
            return redirect()->route('merchandise.detailed', ['merchandise' => $merchandise,]);
        }
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }
}
