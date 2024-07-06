<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubCategoryExport;

class SubCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $subCategories = SubCategory::paginate($pageSize);

        return view('admin.product.data-sub-category', compact('subCategories'));
    }

    public function create()
    {
        $mainCategories = MainCategory::all();
        return view('admin.sub-category.create', compact('mainCategories')); // Update to the correct view for creating a sub-category
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories',
            'description' => 'nullable|string|max:255',
            'main_category_id' => 'required|exists:main_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SubCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'main_category_id' => $request->main_category_id,
            'modified_by' => $user->email,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $subCategory = SubCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
            'description' => 'nullable|string|max:255',
            'main_category_id' => 'required|exists:main_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $subCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'main_category_id' => $request->main_category_id,
            'modified_by' => $user->email,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category updated successfully.');
    }

    public function show($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        return view('admin.sub-category.show', compact('subCategory'));
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $mainCategories = MainCategory::all();
        return view('admin.product.edit-sub-category', compact('subCategory', 'mainCategories'));
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category deleted successfully.');
    }

    public function restore($id)
    {
        $subCategory = SubCategory::withTrashed()->findOrFail($id);
        $subCategory->restore();

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category restored successfully.');
    }

    public function printsubCategories()
    {
        $subCategories = SubCategory::all();
        return view('admin.product.print-sub-category', compact('subCategories'));
    }

    public function exportexcel() 
    {
        return Excel::download(new SubCategoryExport, 'sub_categories.xlsx');
    }

    public function exportsubCategories()
    {
        $subCategories = SubCategory::all();
        return view('admin.product.export-data-sub-category', compact('subCategories'));
    }

    public function addsubcategories()
    {
        $subCategories = SubCategory::all();
        $mainCategories = MainCategory::all(); // Fetch all main categories
        return view('admin.product.add-sub-category', compact('subCategories', 'mainCategories'));
    }

    public function deletedSubCategories()
    {
        $deletedSubCategories = SubCategory::onlyTrashed()->paginate(10);
        return view('admin.product.deleted-sub-categories', compact('deletedSubCategories'));
    }
}
