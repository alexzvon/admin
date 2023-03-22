@extends('master-test')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('test-import') }}" method="POST">
                        @csrf
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000000000" />
                        <input type="file" name="test" id="test">
                        <input type="submit" value="OK">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
