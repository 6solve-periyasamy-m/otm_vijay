<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdditionalCostRequest;
use App\Models\AdditionalCost;
use App\Repository\Costing\AdditionalCostRepository;

class AdditionalCostController extends Controller
{
    public function store(AdditionalCostRequest $request, $model, $id)
    {
        if (!is_numeric($id)) abort(404);
        AdditionalCostRepository::create($model, $id, $request->getData());
        return back();
    }

    public function update(AdditionalCostRequest $request, AdditionalCost $cost)
    {
        $cost->repository->update($request->getData());
        return back();
    }

    public function destroy(AdditionalCost $cost)
    {
        $cost->repository->delete();
        return back();
    }
}
