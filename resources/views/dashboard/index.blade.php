@extends('layouts.app')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        @if(auth()->user()->role === 'admin')
        @include('dashboard.admin')
        @elseif(auth()->user()->role === 'petugas')
        @include('dashboard.petugas')
        @else
        @include('dashboard.peminjam')
        @endif
    </div>
</div>
@endsection