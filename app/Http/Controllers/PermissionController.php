<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupedPermissions = Permission::getGrouped();
        return view('permissions.index', compact('groupedPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        
        // التحقق من وجود أدوار مرتبطة بهذه الصلاحية
        $rolesCount = $permission->roles()->count();
        $usersCount = $permission->users()->count();
        
        if ($rolesCount > 0 || $usersCount > 0) {
            $message = 'لا يمكن حذف هذه الصلاحية لأنها مرتبطة بـ ';
            if ($rolesCount > 0) {
                $message .= $rolesCount . ' دور';
            }
            if ($rolesCount > 0 && $usersCount > 0) {
                $message .= ' و ';
            }
            if ($usersCount > 0) {
                $message .= $usersCount . ' مستخدم';
            }
            
            return redirect()->route('permissions.index')
                ->with('error', $message);
        }
        
        $permission->delete();
        
        return redirect()->route('permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح');
    }
}
