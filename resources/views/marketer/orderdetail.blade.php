@extends('layouts/marketer')

@section('main')
    <style>
        h6 {
            font-size: 15px;
        }
    </style>
    @php
        $total = 0;
        $total2 = 0;
        $cus = DB::table('customers')
            ->where('id', $data[0]->user_id)
            ->first();
        $dis = '';
        $disc = 0;
        $disc2 = 0;
        foreach ($data as $item) {
            if($item->discount > 0 || $item->sdis > 0){
                $disc = $item->discount;
                $disc2 = $item->sdis;
                break;
            }
        }
    @endphp
    <div>
        <div class="right center">
            @if ($data[0]->mainstatus == "blue")
            <div>
                <a class="btn-flat dropdown-trigger" data-target="menu">
                    <i class="material-icons">more_vert</i>
                </a>
                <ul id='menu' class='dropdown-content'>
                    <li><a href="{{ url('/marketer/editorder/' . $data[0]->order_id) }}">Edit</a></li>
                    <li><a href="{{ url('/marketer/deleteorder/' . $data[0]->order_id) }}">Delete</a></li>
                </ul>
            </div>
            @endif
               @if ($data[0]->mainstatus != "blue")
               <div style="margin: 2px 0;">
                <a onclick="printorder('{{ $data[0]->order_id }}');" target="_blank" class="btn-small green accent-4 white-text">
                    Img <i class="material-icons right">file_download</i>
                </a>
            </div>
            <div>
                <a href="{{ url('/marketer/printorder/' . $data[0]->order_id) }}" target="_blank"
                    class="btn-small green accent-4 white-text">
                    PDF <i class="material-icons right">picture_as_pdf</i>
                </a>
            </div>
               @endif
           
               
        </div>

        <div style="font-size: 10px;">
            <h6 style="font-size: 12px;">Customer: {{ $data[0]->name }}</h6>
            <h6 style="font-size: 12px;">Shop Name: {{ $cus->shopname }}</h6>
            <h6 style="font-size: 12px;">order_id: {{ $data[0]->order_id }}</h6>
            <h6 style="font-size: 12px;">Date: {{ date('Y-m-d', strtotime($data[0]->date)) }}</h6>
            <h6 style="font-size: 12px;">Miti: {{ getNepaliDate($data[0]->date) }}</h6>

            @if ($cus->marketer != null)
                <h6 style="font-size: 12px;">Referer: {{ $cus->marketer }}</h6>
            @endif
        </div>


       
            <div class="mp-card" style="overflow-x: scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th class="center">Ordered Quantity</th>
                            <th class="center">Approved Quantity</th>
                            <th class="center">Price</th>
                            <th>Status</th>
                            <th>total</th>
                            <th>Total(discounted)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $item)
                            <tr>
                                <td @if ($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>
                                    {{ $item->item }} @if($item->net != NULL) <span>(NET)</span> @endif
                                <br>
                            <span style="font-size: 5px; margin-top:-10px;">{{$item->brand}} {{$item->category}}</span></td>
                                <td class="center">{{ $item->quantity }}</td>
                                <td class="center">{{ $item->approvedquantity }}</td>
                                <td class="center">{{ $item->price }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    @if ($item->status == 'approved')
                                        {{ money($a = $item->approvedquantity * $item->price) }}
                                        <span class="hide">{{ $total = $total + $a }}</span>
                                    @elseif($item->status == 'pending')
                                        {{ money($a = $item->quantity * $item->price) }}
                                        <span class="hide">{{ $total = $total + $a }}</span>
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 'approved')
                                        {{ money($b = ($item->approvedquantity * $item->price * (1-0.01*$item->discount)) * (1-0.01*$item->sdis))}}
                                        <span class="hide">{{ $total2 = $total2 + $b }}</span>
                                    @elseif($item->status == 'pending')
                                        {{ money($b = ($item->quantity * $item->price * (1-0.01*$item->discount)) * (1-0.01*$item->sdis))}}
                                        <span class="hide">{{ $total2 = $total2 + $b }}</span>
                                    @else
                                        0
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Total</td>
                            <td style="font-weight: 700">{{ money($total) }}</td>
                            <td style="font-weight: 700">{{ money($total2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">(First/Cash) Discount</td>
                            <td>{{ $disc }}%</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">(Direct/Net) Discount</td>
                            <td>{{ $disc2 }}%</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Net Total</td>
                            <td style="font-weight: 700">{{ money($total2) }}</td>
                            <td style="font-weight: 700">{{ money($total2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mp-card" style="margin-top: 30px;">
                <h6>User Remarks: {{ $data['0']->userremarks }}</h6>
            </div>
            <div class="bg-content mp-card row" style="margin-top:30px;">
                <div class="col s12">
                    Remarks: {{ $data['0']->remarks }}
                </div>
                <div class="col s12">
                    Cartoons: {{ $data['0']->cartoons }}
                </div>
                <div class="col s12">
                   Transport: {{ $data['0']->transport }}
                </div>
            </div>
    </div>

    <script>
         function printorder(id) {
            window.open('/marketer/saveorder/' + id, '_blank', 'toolbar=0,location=0,menubar=0');
        }
    </script>
@endsection
