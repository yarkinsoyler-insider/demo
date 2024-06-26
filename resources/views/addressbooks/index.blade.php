
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AddressBook') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('addressbooks.create') }}" class="btn btn-primary">Add New Address</a>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="padding: 20px;">
                    <div class="addressbook-list">
                        <ul>
                            @foreach ($addressbooks as $addressbook)
                                <li>
                                    <div>
                                        <a href="{{ route('addressbooks.show', $addressbook->id) }}">{{ $addressbook->name }}</a>
                                    </div>
                                    <div>
                                        <a href="{{ route('addressbooks.edit', $addressbook->id) }}" class="edit-button">Edit</a>
                                        <form action="{{ route('addressbooks.destroy', $addressbook->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>


