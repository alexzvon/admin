@extends('master')
@section('content')
    <div style="
            background-color: white;
            text-align: center;
            font-family: serif;
            font-size: x-large;">
        @if($link)
            <a href="{{ $link ?? null }}" style="color: cornflowerblue;">{{ $link }}</a>
        @else
            <p style="color: red;">Something went wrong, please try again later!</p>
        @endif
    </div>
@endsection