<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchandise\MerchandiseRequest;
use App\Models\Merchandise\Merchandise;
use App\Repository\Model\Merchandise\MerchandiseRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    public function index(Request $request)
    {
        $archived = $request->archived ?? false;
        if ($archived) {
            $merch = Merchandise::withCount('inventory')->get();
        } else {
            $merch = Merchandise::withCount('inventory')->where('archived', '=', false)->get();
        }
        return view('pages.admin.merchandise.table', ['merchandise' => $merch, 'archived' => $archived]);
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

    public function duplicate(Merchandise $merchandise): RedirectResponse
    {
        $merchandise->repository->duplicate(true);
        return redirect()->route('merchandise.edit', ['merchandise' => $merchandise,]);
    }

    public function archive(Merchandise $merchandise): RedirectResponse
    {
        $merchandise->archived = !$merchandise->archived;
        $merchandise->save();

        if (!$merchandise->archived) {
            return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
        }

        return redirect()->route('merchandise.all');
    }
}
