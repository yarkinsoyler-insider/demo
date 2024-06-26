<?php

// app/Http/Controllers/AddressBookController.php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AddressBookController extends Controller
{
     use AuthorizesRequests;

    public function index()
    {
        $addressbooks = AddressBook::where('user_id', Auth::id())->get();
        return view('addressbooks.index', compact('addressbooks'));
    }

    public function create()
    {
        return view('addressbooks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        AddressBook::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('addressbooks.index');
    }

    public function show(AddressBook $addressbook)
    {
        $this->authorize('view', $addressbook);
        return view('addressbooks.show', compact('addressbook'));
    }

    public function edit(AddressBook $addressbook)
    {
        $this->authorize('update', $addressbook);
        return view('addressbooks.edit', compact('addressbook'));
    }

    public function update(Request $request, AddressBook $addressbook)
    {
        $this->authorize('update', $addressbook);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $addressbook->update($request->only(['name', 'phone', 'email', 'address']));

        return redirect()->route('addressbooks.index');
    }

    public function destroy(AddressBook $addressbook)
    {
        $this->authorize('delete', $addressbook);
        $addressbook->delete();
        return redirect()->route('addressbooks.index');
    }
}

