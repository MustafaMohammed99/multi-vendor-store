<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

use NumberFormatter;

class AdminsController extends Controller
{
    use PasswordValidationRules;

    public function __construct()
    {
        $this->authorizeResource(Admin::class, 'admin');
    }

    public function index()
    {
        // $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        // $formatter = new NumberFormatter('en', NumberFormatter::CURRENCY);

        // dd( $formatter->formatCurrency(1052, 'ILS'));
        // dd( Currency::format(100));

        $admins = Admin::paginate(7);
        return view('dashboard.admins.index', compact('admins'));
    }


    public function create()
    {
        return view('dashboard.admins.create', [
            'roles' => Role::all(),
            'admin' => new Admin(),
        ]);
    }


    public function store(AdminRequest $request)
    {
        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
        $admin = Admin::create($data);
        $admin->roles()->attach($request->roles);

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin created successfully');
    }


    public function show($admin)
    {
        //
    }


    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $admin_roles = $admin->roles()->pluck('id')->toArray();

        return view('dashboard.admins.edit', compact('admin', 'roles', 'admin_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        $data = $request->except('password');
        if ($request->password !== null) {
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        $admin->roles()->sync($request->roles);

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        // Admin::destroy($admin);
        $admin->delete();
        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin deleted successfully');
    }
}
