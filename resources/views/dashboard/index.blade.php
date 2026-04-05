@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'admin')
@include('dashboard.admin')
@elseif(auth()->user()->role === 'petugas')
@include('dashboard.petugas')
@else
@include('dashboard.peminjam')
@endif
@endsection