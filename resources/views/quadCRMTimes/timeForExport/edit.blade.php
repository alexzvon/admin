@extends('master')
@section('content')
    <div style="background-color: white; width: 50%; padding: 28px">
        <form action="{{ route('update-export-time', $exportTime->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="exampleInputTime" style="font-size: large">Time</label>
                <input type="time" class="form-control" value="{{ $exportTime->time }}" name="time" id="exampleInputTime" placeholder="Enter time">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection