<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainCategoriesController extends Controller
{
    /**
     * Display a listing of the main categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mainCategories = MainCategory::all();
        return view('admin.specification.data-main-category', compact('mainCategories'));
    }

    /**
     * Show the form for creating a new main category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.specification.data-category');
    }

    /**
     * Store a newly created main category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:main_categories',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MainCategory::create($request->all());

        return redirect()->route('main-categories.index')->with('success', 'Main Category created successfully.');
    }

    /**
     * Display the specified main category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        return view('admin.category.show', compact('mainCategory'));
    }

    /**
     * Show the form for editing the specified main category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        return view('admin.category.edit', compact('mainCategory'));
    }

    /**
     * Update the specified main category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $mainCategory = MainCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:main_categories,name,' . $mainCategory->id,
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mainCategory->update($request->all());

        return redirect()->route('main-categories.index')->with('success', 'Main Category updated successfully.');
    }

    /**
     * Remove the specified main category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        $mainCategory->delete();

        return redirect()->route('main-categories.index')->with('success', 'Main Category deleted successfully.');
    }
}
