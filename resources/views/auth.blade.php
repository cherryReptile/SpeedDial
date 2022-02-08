@extends('layout')

@section('title')
    Auth
@endsection

@section('body')
    <form action="{{route('auth.post')}}" method="post">
        @csrf
        <input type="email" name="email" placeholder="email">
        <input type="password" name="password" placeholder="password">
        <button type="submit">Отправить</button>
    </form>
@endsection
