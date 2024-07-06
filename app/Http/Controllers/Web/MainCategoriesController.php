<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoryExport;

class MainCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $mainCategories = MainCategory::paginate($pageSize);

        return view('admin.product.data-categories', compact('mainCategories'));
    }

    public function create()
    {
        return view('admin.category.create'); // Update to the correct view for creating a main category
    }

    public function store(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:main_categories',
        'description' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    MainCategory::create([
        'name' => $request->name,
        'description' => $request->description,
        'modified_by' => $user->email,
    ]);

    return redirect()->route('categories.index')->with('success', 'Main Category created successfully.');
}

public function update(Request $request, $id)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    $mainCategory = MainCategory::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:main_categories,name,' . $mainCategory->id,
        'description' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $mainCategory->update([
        'name' => $request->name,
        'description' => $request->description,
        'modified_by' => $user->email,
    ]);

    return redirect()->route('categories.index')->with('success', 'Main Category updated successfully.');
}



    public function show($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        return view('admin.category.show', compact('mainCategory'));
    }

    public function edit($id)
    {
        $category = MainCategory::findOrFail($id);
        return view('admin.product.edit-category', compact('category'));
    }

    public function destroy($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        $mainCategory->delete();
    
        return redirect()->route('categories.index')->with('success', 'Main Category deleted successfully.');
    }
    
    public function restore($id)
    {
        $mainCategory = MainCategory::withTrashed()->findOrFail($id);
        $mainCategory->restore();
    
        return redirect()->route('categories.index')->with('success', 'Main Category restored successfully.');
    }
    public function printCategories()
    {
        $mainCategories = MainCategory::all();
        return view('admin.product.print-categories', compact('mainCategories'));
    }

    public function exportexcel() 
    {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }

    public function exportCategories()
    {
        $mainCategories = MainCategory::all();
        return view('admin.product.export-data-categories', compact('mainCategories'));
    }
    public function addcategories()
    {
        $mainCategories = MainCategory::all();
        return view('admin.product.add-categories', compact('mainCategories'));
    }
    public function deletedCategories()
    {
        $deletedCategories = MainCategory::onlyTrashed()->paginate(10);
        return view('admin.product.deleted-categories', compact('deletedCategories'));
    }

}

