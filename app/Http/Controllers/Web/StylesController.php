<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StyleExport;

class StylesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $styles = Style::paginate($pageSize);

        return view('admin.product.data-style', compact('styles'));
    }

    public function printStyle()
    {
        $styles = Style::all();
        return view('admin.product.print-style', compact('styles'));
    }

    public function exportexcel() 
    {
        return Excel::download(new StyleExport, 'styles.xlsx');
    }

    public function exportStyle()
    {
        $styles = Style::all();
        return view('admin.product.export-data-style', compact('styles'));
    }

    public function addstyle()
    {
        return view('admin.product.add-style');
    }

    public function create()
    {
        return view('admin.product.add-style');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();  
        $request->image->storeAs('styles', $imageName, 'public');

        Style::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
            'modified_by' => Auth::user()->email,
        ]);

        return redirect()->route('styles.index')->with('success', 'Style created successfully.');
    }

    public function edit($id)
    {
        $style = Style::findOrFail($id);
        return view('admin.product.edit-style', compact('style'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $style = Style::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->storeAs('styles', $imageName, 'public');
            Storage::disk('public')->delete('styles/'.$style->image);
            $style->image = $imageName;
        }

        $style->name = $request->name;
        $style->description = $request->description;
        $style->modified_by = Auth::user()->email;
        $style->save();

        return redirect()->route('styles.index')->with('success', 'Style updated successfully.');
    }

    public function destroy($id)
    {
        $style = Style::findOrFail($id);
        $style->delete();
    
        return redirect()->route('styles.index')->with('success', 'Style deleted successfully.');
    }

    public function restore($id)
    {
        $style = Style::withTrashed()->findOrFail($id);
        $style->restore();
    
        return redirect()->route('styles.index')->with('success', 'Style restored successfully.');
    }

    public function deletedStyle()
    {
        $deletedStyles = Style::onlyTrashed()->paginate(10);
        return view('admin.product.deleted-style', compact('deletedStyles'));
    }
}

