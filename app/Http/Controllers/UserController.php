<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('id', 'name', 'email')->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editbtn = '<a href="' . route("users.edit", ['id' => $row->id]) . '" class="btn btn-primary btn-sm m-1">Edit</a>';
                    // $deletebtn = '<a href="' . route("users.delete", ['id' => $row->id]) . '" class="btn btn-primary btn-sm m-1 delete-ticket" data-user-id="' . $row->id . '">Delete</a>';
                    $deletebtn = '<button class="btn btn-primary btn-sm m-1 delete-user" data-user-id="' . $row->id . '">Delete</button>';
                    // $deletebtn = '<a class="btn btn-danger" onclick="return confirm("Are you sure?")" href="' . route("users.delete", ['id' => $row->id]) . '"><i class="fa fa-trash"></i></a>';
                    return $editbtn . $deletebtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email',
                'password' => 'required',
                'password_confirmation' => 'required'
            ]);

            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (ValidationException $e) {
            // If validation fails, catch the exception and handle the errors
            return redirect()->route('users.create')->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit')->with(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email'
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->save();

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (ValidationException $e) {
            // If validation fails, catch the exception and handle the errors
            // return redirect()->route('tickets.edit')->withErrors($e->errors())->withInput();
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response(['success' =>'User deleted successfully.', 'status' => true]);
    }
}
