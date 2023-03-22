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
            @if(!is_null($exportTime))
                <tr>
                    <th scope="row">{{ $exportTime->id }}</th>
                    <td style="font-size: large">{{ $exportTime->admin->name ?? "Added by SuperAdmin" }}</td>
                    <td style="font-size: large">{{ $exportTime->time }}</td>
                    <td>
                        <a href="{{ route('edit-export-time', $exportTime->id) }}"
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