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
        $merch = Merchandise::withCount('inventories')->get();
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

    public function edit(Merchandise $merchandise)
    {
        return view('pages.admin.merchandise.form', ['merchandise' => $merchandise,]);
    }

    public function update(MerchandiseRequest $request, Merchandise $merchandise)
    {
        $merchandise->repository->updateWithImage($request->getDataset(), $request->image);
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function destroy(Merchandise $merchandise)
    {

    }
}
