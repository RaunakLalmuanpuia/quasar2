<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\RoleApply;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        if (Auth::user()->hasRole('admin')){
            $pendingRoles = RoleApply::with(['users'])->where('role_status', 'pending')->get();
            
            return Inertia::render('User/Approve',[
                'pendingRoles' => $pendingRoles
            ]);
        }
        else {
            $user_id = auth()->user()->id;
            $existingApplication = RoleApply::where('user_id', $user_id)
            ->where('role_status', 'pending')
            ->exists();
            if ($existingApplication) {
                // User already applied for role
                return redirect()->route('dashboard')->with('message', 'Already applied for role. Please wait for verification');
            }
            // Assuming 'admin' is the name of the admin role
            $roles = Role::with('permissions')->where('name', '<>', 'admin')->get();
            return Inertia::render('User/Create', [
                'role' => $roles
            ]);
        }    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Apply for role
        // dd($request);
        $role = new RoleApply([
            'user_id' => auth()->user()->id,
            'email' => $request->email,
            'address' => $request->address,
            'company_id' => $request->id,
            'role_applied' => $request->post_name,
            // 'role_status' => 'pending',
           

        ]);
        $role->save();
        return redirect()->route('dashboard')->with('message', 'Successfully Applied for role!');


        // if ($request->post_name == "manager") {
        //     dd($request);
        // }
        // else{
        //     dd($request);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($id);
        // dd($request);
        // dd(RoleApply::where('id', $id)->get());
        $applyRole = RoleApply::where('id', $id)->first();
        if($request->status === "Approve"){
            $user = User::find($applyRole->user_id);
            $user->syncRoles($applyRole->role_applied);
            // $user->assignRole($applyRole->role_applied);
            $applyRole->role_status = 'accepted';
            $applyRole->save();
            return redirect()->route('dashboard')->with('message', 'Role Applied to user');
        }else { // if rejected
            $applyRole->role_status = 'rejected';
            $applyRole->save();
            return redirect()->route('dashboard')->with('message', 'Role Rejected');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
    public function rolesUser()
    {
        $roles = Role::with('permissions')->get();
        return Inertia::render('User/Role', [
            'roles' => $roles
        ]);
    }
    public function storeRole(Request $request){
        // dd($request);
        $request->validate([
            'name' => ['required', 'string'],
            'permissions' => ['array'],
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $role->givePermissionTo($request->input('permissions'));

        return redirect()->route('rolesUser');
    }
    public function editRole(Role $role)
    {
        $permissions = Permission::pluck('name', 'id');

        return view('PermissionsUI::roles.edit', compact('role', 'permissions'));
    }
    public function updateRole(Request $request, Role $role)
    {
        dd($role);
        $request->validate([
            'name' => ['required', 'string'],
            'permissions' => ['array'],
        ]);

        $role->update(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('rolesUser');
    }
    public function destroyRole(Role $role)
    {
        // dd($role);
        $role->delete();
        return redirect()->route('rolesUser');
    }

}
