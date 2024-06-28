<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\AccountCreationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        return inertia('Users/Index', [
            'users' => User::withTrashed()
                           ->where(function ($query) use ($search) {
                               $query->when($search, function ($query) use ($search) {
                                   $query->where('name', 'LIKE', "%{$search}%")
                                         ->orWhere('email', 'LIKE', "%{$search}%");
                               });
                           })
                           ->when($request->sortBy, function ($query, $sortBy) {
                               $query->orderBy($sortBy, request()->boolean('sortDesc') ? 'desc' : 'asc');
                           }, function ($query) {
                               $query->orderBy('id', 'desc');
                           })
                           ->paginate($request->perPage)
                           ->withQueryString(),
            'filters' => [
                'page' => $request->page,
                'search' => $request->search,
                'sortBy' => $request->sortBy,
                'sortDesc' => $request->sortDesc,
                'perPage' => $request->perPage,
            ]
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return inertia('Users/Form');
    }

    /**
     * Store a newly created user in database.
     */
    public function store(UserRequest $request)
    {
        $valid = $request->validated();

        if ($request->hasFile('image'))
            $valid['image'] = $request->file('image')->store('ProfileImages');

        $password = Str::password(16);
        $valid['password'] = $password;

        $user = User::create($valid);

        Mail::to($user->email)->send(new AccountCreationMail($user, $password));

        return to_route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return inertia('Users/Form', ['user' => $user]);
    }

    /**
     * Update the specified user in database.
     */
    public function update(UserRequest $request, User $user)
    {
        $valid = $request->validated();

        if ($request->hasFile('image'))
        {
            if ($user->getRawOriginal('image') && Storage::exists($user->getRawOriginal('image')))
                Storage::delete($user->getRawOriginal('image'));

            $valid['image'] = $request->file('image')->store('ProfileImages');
        }
        else
            $valid = Arr::except($valid, 'image');

        $user->update($valid);

        return to_route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Deactivate the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->user()->id)
            return back()->with('error', 'Can not deactivate yourself');

        $user->delete();

        return back()->with('success', 'User deactivated successfully');
    }

    /**
     * Restore the specified user.
     */
    public function restore($user)
    {
        User::onlyTrashed()->find($user)->restore();

        return back()->with('success', 'User reactivated successfully');
    }

    /**
     * Delete the specified user from database.
     */
    public function delete($user)
    {
        $user = User::onlyTrashed()->find($user);

        if ($user->getRawOriginal('image') && Storage::exists($user->getRawOriginal('image')))
            Storage::delete($user->getRawOriginal('image'));

        $user->forceDelete();

        return to_route('users.index')->with('success', 'User deleted successfully');
    }

    public function getImage($user)
    {
        $user = User::withTrashed()->findOrFail($user);

        if ($user->getRawOriginal('image'))
            return Storage::response($user->getRawOriginal('image'));

        return null;
    }
}
