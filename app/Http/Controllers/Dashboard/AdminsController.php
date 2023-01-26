<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
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

    // public function __construct()
    // {
    //     $this->authorizeResource(Admin::class, 'admin');
    // }
    /*
     <style>
        body {
            font-family: 'lateef';
        }
    </style>
    */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        // $formatter = new NumberFormatter('en', NumberFormatter::CURRENCY);

        // dd( $formatter->formatCurrency(1052, 'ILS'));
        // dd( Currency::format(100));


        $admins = Admin::paginate();
        return view('dashboard.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $admins = Admin::all();
        $pdf = PDF::loadView(
            'dashboard.admins.pdf',
            ['data' => $admins],
            [],
            ['orientation' => 'L']
        );
        // "'Baloo Bhaijaan 2','sans-serif'"

    //     <table border="1px">
    //     <caption></caption>
    //     <tr>
    //         <th rowspan="2">اسم المحفظ </th>
    //         <th rowspan="2">اسم المسجد:</th>
    //         <th rowspan="2">عن شهر: </th>
    //         <th rowspan="2">بيانات التقرير</th>
    //     </tr>
    // </table>
        $pdf->stream(Auth::user()->name . '.pdf');

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'save pdf successfully' . public_path('report-month/') . Auth::user()->name . '.pdf');


        return view('dashboard.admins.create', [
            'roles' => Role::all(),
            'admin' => new Admin(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->roles);

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'username' => 'required|string|unique|max:255',
        //     'phone_number' => 'required|unique|max:10',
        //     'password' => 'required',
        //     'roles' => 'required|array',
        //     'email' => 'required|string|email|unique:users,email',
        // ]);
        dd($request->roles);
        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
        $admin = Admin::create($data);
        $admin->roles()->attach($request->roles);

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
        ]);

        $admin->update($request->all());
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
    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin deleted successfully');
    }
}
