<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tour\Category\TourCategoryRequest;
use App\Models\Tour\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TourCategoryController extends Controller
{

    public function index()
    {
        return redirect()->route('attributes.edit');
    }

    public function create()
    {
        return view('pages.admin.tour.category.form');
    }

    /**
     * Store a new Category, then redirect back to the return url
     * @param TourCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(TourCategoryRequest $request): RedirectResponse
    {
        $tourCategory = TourCategory::create(['name' => $request->name, 'display_mode_type' => $request->display_mode_type, 'display_mode_color' => $request->display_mode_color,]);
        return $tourCategory->repository->getReturnURL();
    }

    /**
     * Redirect back to the return url
     * @param TourCategory $category
     * @return RedirectResponse
     */
    public function view(TourCategory $category): RedirectResponse
    {
        return $category->repository->getReturnURL();
    }

    public function edit(TourCategory $category)
    {
        return view('pages.admin.tour.category.form', ['category' => $category,]);
    }

    public function update(TourCategoryRequest $request, TourCategory $category): RedirectResponse
    {
        $category->update(['name' => $request->name, 'display_mode_type' => $request->display_mode_type, 'display_mode_color' => $request->display_mode_color,]);
        return $category->repository->getReturnURL();
    }

    public function destroy(TourCategory $category)
    {
        if ($category->tours()->count() > 0) {
            return back()->withErrors(['msg' => 'Cannot delete a category with linked tours.']);
        }
        $category->delete();
        return redirect()->route('attributes.edit');
    }
}
