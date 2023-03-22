@extends('master')
@section('content')
    <div style="background-color: white">
        <table class="table">
            <thead style="background-color: gray">
            <tr style="color: white">
                <th scope="col">#</th>
                <th scope="col">NAME</th>
                <th scope="col">DESCRIPTION</th>
                <th scope="col">WAREHOUSE_CITY</th>
                <th scope="col">ADD</th>
                <th scope="col">DELETE</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <th scope="row">{{ $supplier->id }}</th>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->description }}</td>
                    <td>{{ $supplier->warehouse_city }}</td>
                    <td>
                        @if($supplier->quadCRMSupplier)
                            <a href="{{ route('add-to-quadCRM', $supplier->id) }}"
                               title="Add to QuadCRM">
                                <button disabled="disabled" type="button" class="btn btn-labeled btn-success">
                                    ADD
                                </button>
                            </a>
                        @else
                            <a href="{{ route('add-to-quadCRM', $supplier->id) }}"
                               title="Add to QuadCRM">
                                <button type="button" class="btn btn-labeled btn-success">
                                    ADD
                                </button>
                            </a>
                        @endif
                    </td>
                    <td>
                        @if($supplier->quadCRMSupplier)
                            <a href="{{ route('delete-from-quadCRM', $supplier->id) }}" title="Delete from QuadCRM">
                                <button type="button" class="btn btn-labeled btn-danger">
                                    DELETE
                                </button>
                            </a>
                        @else
                            <a href="{{ route('delete-from-quadCRM', $supplier->id) }}" title="Delete from QuadCRM">
                                <button disabled="disabled" type="button" class="btn btn-labeled btn-danger">
                                    DELETE
                                </button>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin: auto; width: 50%; padding: 5px;">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection