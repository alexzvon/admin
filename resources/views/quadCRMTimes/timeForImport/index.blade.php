@extends('master')
@section('content')
    <div style="background-color: white">
        <table class="table">
            <thead style="background-color: gray">
            <tr style="color: white">
                <th scope="col">#</th>
                <th scope="col">ADMIN NAME</th>
                <th scope="col">TIME</th>
                <th scope="col">CHANGE</th>
            </tr>
            </thead>
            <tbody>
            @if(!is_null($importTime))
                <tr>
                    <th scope="row">{{ $importTime->id }}</th>
                    <td style="font-size: large">{{ $importTime->admin->name ?? "Added by SuperAdmin" }}</td>
                    <td style="font-size: large">{{ $importTime->time }}</td>
                    <td>
                        <a href="{{ route('edit-import-time', $importTime->id) }}"
                           title="Change">
                            <button type="button" class="btn btn-labeled btn-info">
                                CHANGE
                            </button>
                        </a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection