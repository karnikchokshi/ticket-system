<?php

namespace App\Http\Controllers;

use App\Events\EmailSent;
use App\Models\RolesPermissions;
use App\Models\Tickets;
use App\Models\User;
use App\Notifications\EmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tickets::with(['users' => function ($q) {
                $q->select('id', 'name');
            }])
                ->select('id', 'title', 'description', 'user_id', 'status')->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $userPermissions = RolesPermissions::whereHas('user', function ($q) {
                        $q->where('id', Auth::user()->id);
                    })
                        ->with(['role' => function ($r) {
                            $r->select('id', 'name');
                        }])
                        ->with(['permission' => function ($p) {
                            $p->select('id', 'name');
                        }])
                        ->first();
                    $editbtn = $deletebtn = '';
                    $editbtn = '<a href="' . route("tickets.edit", ['id' => $row->id]) . '" class="btn btn-primary btn-sm m-1">Edit</a>';
                    if ($userPermissions && $userPermissions->role->name == 'editor' && in_array($userPermissions->permission->name, config('constants.Roles_Permissions.Editor'))) {
                        // $deletebtn = '<a href="' . route("tickets.delete", ['id' => $row->id]) . '" class="btn btn-primary btn-sm m-1">Delete</a>';
                        $deletebtn = '<button class="btn btn-primary btn-sm m-1 delete-ticket" data-ticket-id="' . $row->id . '">Delete</button>';
                    }
                    return $editbtn . $deletebtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('tickets.create')->with(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'user_id' => 'required',
                'status' => 'in:Pending,Closed',
            ]);

            $ticket = new Tickets;
            $ticket->title = $request->get('title');
            $ticket->description = $request->get('description');
            $ticket->user_id = $request->get('user_id') ?? Auth::user()->id;
            $ticket->status = $request->get('status') ?? 'Pending';
            $ticket->save();

            /* Send notification code */
            // $user = User::findOrFail($request->get('user_id'));

            // $user->notify(
            //     new EmailNotification($ticket, $user)
            // );

            /* Send notification code using Event*/
            // $user = $request->get('user_id');
            // $message = 'Ticket has been assigned to you.';
            // event(new EmailSent($user, $message));

            return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
        } catch (ValidationException $e) {
            // If validation fails, catch the exception and handle the errors
            return redirect()->route('tickets.create')->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(tickets $tickets)
    public function edit($id)
    {
        $ticket = Tickets::with(['users' => function ($q) {
            $q->select('id', 'name');
        }])
            ->select('id', 'title', 'description', 'user_id', 'status')
            ->where('id', $id)
            ->first();

        $users = User::pluck('name', 'id');
        return view('tickets.edit')->with(['ticket' => $ticket, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'user_id' => 'required',
                'status' => 'in:Pending,Closed',
            ]);

            $ticket = Tickets::findOrFail($id);
            $ticket->title = $request->get('title');
            $ticket->description = $request->get('description');
            $ticket->user_id = $request->get('user_id');
            $ticket->status = $request->get('status') ?? 'Pending';
            $ticket->save();

            /* Send notification code */
            // $user = User::findOrFail($request->get('user_id'));

            // $user->notify(
            //     new EmailNotification($ticket, $user)
            // );


            /* Send notification code using Event*/
            // $user = User::findOrFail($request->get('user_id'));
            // $message = 'Ticket has been assigned to you.';
            // event(new EmailSent($user, $message));

            return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
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
        Tickets::findOrFail($id)->delete();
        // return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
        return response(['success' => 'Ticket deleted successfully.', 'status' => true]);
    }
}
