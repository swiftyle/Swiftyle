<?php

// App/Http/Controllers/Web/UserController.php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\Auth\AuthenticationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UsersController extends AuthenticationController
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    
     public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $users = User::paginate($pageSize);

        // Additional data you may want to pass to the view
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'Active')->count();
        $inactiveUsers = User::where('status', 'Inactive')->count();

        return view('admin.users.data-user', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
        ]);
    }
    
    public function developer()
    {
        $users = User::where('role', 'Admin')->get();
        return view('admin.developer-team.developer',compact('users'));

    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users',
            'email' => 'sometimes|string|email|max:255|unique:users',
            'phone' => 'sometimes|string|max:255|unique:users',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('admin.profile.edit-profile', compact('user'));
    }

    public function showProfile()
    {
        $user = Auth::user();  // Get the logged-in user

        // Set the user's name in the session

        return view('admin.profile.edit-profile', compact('user'));  // Pass the user data to the view
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the avatar field
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $data = $request->except(['_token', '_method']);
    
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('assets/images'), $avatarName);
            $data['avatar'] = 'assets/images/' . $avatarName;
        }
    
        $user->update($data);
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    

    /**
     * Remove the specified user from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    public function printUsers()
    {
        $users = User::all();
        return view('admin.users.print', compact('users'));
    }

    public function exportexceluser() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportuser()
    {
        $users = User::all();
        return view('admin.users.exportuser', compact('users'));
    }

    public function delete($id)
    {
        // Implement delete logic to soft delete user
        $user = User::findOrFail($id);
        $user->status = 'Inactive'; // Change status to Inactive
        $user->deleted_at = now(); // Set deleted_at timestamp
        $user->save();

        // Redirect back or somewhere else after deletion
        return redirect()->route('users.index');
    }

    public function restore($id)
    {
        // Implement restore logic to activate user
        $user = User::withTrashed()->findOrFail($id);
        $user->status = 'Active'; // Change status to Active
        $user->deleted_at = null; // Clear deleted_at timestamp
        $user->save();

        // Redirect back or somewhere else after restoration
        return redirect()->route('users.index');
    }
    
    public function deletedUsers()
    {
        $deletedUsers = User::onlyTrashed()->paginate(10);
        return view('admin.users.deleted-user', compact('deletedUsers'));
    }
}
