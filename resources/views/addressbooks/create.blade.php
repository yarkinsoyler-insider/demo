<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AddressBook') }}
        </h2>
    </x-slot>
    <h1>Create Address Book Entry</h1>
    <div class="form-container" style="padding: 20px;">
        <form method="POST" action="{{ route('addressbooks.store') }}">
            @csrf
            <div>
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <div>{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
                @error('phone')
                    <div>{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Email</label>
                <input type="text" name="email" value="{{ old('email') }}">
                @error('email')
                    <div>{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address') }}">
                @error('address')
                    <div>{{ $message }}</div>
                @enderror
            </div>
            <div>
                <button type="submit">Create</button>
            </div>
        </form>
    </div>
    <a href="{{ route('addressbooks.index') }}">Back</a>
</x-app-layout>
