@extends('layouts.seller')

@section('content')
<div class="container">
    <div class="orders-wrapper">
        <h1 class="page-title">Order History</h1>

        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Field Name</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Changed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderHistories as $history)
                        <tr>
                            <td>{{ $history->order_id }}</td>
                            <td>{{ $history->field_name }}</td>
                            <td>{{ $history->old_value }}</td>
                            <td>{{ $history->new_value }}</td>
                            <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
