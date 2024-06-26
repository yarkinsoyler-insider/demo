<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AddressBook') }}
        </h2>
    </x-slot>
    <div style="padding: 20px;">
    <h1>Show Address Book Entry</h1>
    <div>
        <strong>Name:</strong> {{ $addressbook->name }}
    </div>
    <div>
        <strong>Phone:</strong> {{ $addressbook->phone }}
    </div>
    <div>
        <strong>Email:</strong> {{ $addressbook->email }}
    </div>
    <div>
        <strong>Address:</strong> {{ $addressbook->address }}
    </div>
 <a href="{{ route('addressbooks.index') }}">Back</a>
</div>

</x-app-layout>
