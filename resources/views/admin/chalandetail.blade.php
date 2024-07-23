@extends('layouts/admin')

@section('main')
    <div>
        <div class="center" style="margin-top: 20px;">
            <div class="center">{{ $data[0]->name.shopname($data[0]->user_id) }}</div>
            <div class="center">{{$data[0]->order_id}}</div>
            <div class="center">{{$data[0]->date}}</div>
            <div class="center">{{getNepaliDate($data[0]->date)}}</div>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Item</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sn = 0;
                    @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$sn = $sn + 1 }}</td>
                            <td>
                                {{ $item->item }} @if($item->net != NULL) <span>(NET)</span> @endif
                            <br>
                        <span style="font-size: 7px; margin-top:-10px;">{{$item->brand}} {{$item->category}}</span></td>
                            <td>{{$item->approvedquantity}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                Remarks: {{$data[0]->remarks}}
            </div>
        </div>
    </div>
@endsection