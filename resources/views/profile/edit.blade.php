@extends('layouts.app')

@section('title', 'Profil bearbeiten')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Mein Profil</h1>

    @include('profile.partials.update-profile-information-form')

    <hr class="my-6">

    @include('profile.partials.update-password-form')

    <hr class="my-6">

    @include('profile.partials.delete-user-form')
@endsection