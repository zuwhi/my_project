@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Profile Picture Upload -->
            <div class="mt-4">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" />
            </div>

            <!-- Display current profile picture if exists -->
            @if (Auth::user()->profile_picture)
                <div class="mt-4">
                    <img src="{{ Storage::disk('s3')->url(Auth::user()->profile_picture) }}" alt="Profile Picture" width="150">
                </div>
            @endif

            <!-- Other profile fields, like name and email -->
            <div class="mt-4">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
            </div>

            <div class="mt-4">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
            </div>

            <div class="mt-4">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection
