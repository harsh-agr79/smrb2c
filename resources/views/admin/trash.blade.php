@extends('layouts/admin')

@section('main')
    <div class="container">
        <div class="mp-card">
            <h5 class="center">Orders</h5>
            <table>
                <thead>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Orderid</th>
                    <th>Restore</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($orders as $item)
                    <tr>
                        <td>{{$item->date}}</td>
                        <td>{{$item->name.shopname($item->user_id)}}</td>
                        <td>{{$item->order_id}}</td>
                        <td><a href="{{url('/restore/order/'.$item->order_id) }}" class="btn green"><i class="material-icons">autorenew</i></a></td>
                        <td><a href="{{url('/trashdel/order/'.$item->order_id) }}" class="btn red"><i class="material-icons">delete</i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mp-card" style="margin-top: 20px;">
            <h5 class="center">Payments</h5>
            <table>
                <thead>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Payment ID</th>
                    <th>Amount</th>
                    <th>Restore</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($payments as $item)
                        <tr>
                            <td>{{$item->date}}</td>
                            <td>{{$item->name.shopname($item->user_id)}}</td>
                            <td>{{$item->paymentid}}</td>
                            <td>{{$item->amount}}</td>
                            <td><a href="{{url('/restore/payment/'.$item->paymentid) }}" class="btn green"><i class="material-icons">autorenew</i></a></td>
                            <td><a href="{{url('/trashdel/payment/'.$item->paymentid) }}" class="btn red"><i class="material-icons">delete</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection