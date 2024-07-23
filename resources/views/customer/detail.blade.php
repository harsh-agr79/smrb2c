@extends('layouts/customer')

@section('main')
    @php
        $total = 0;
        $total2 = 0;
        $cus = DB::table('customers')
            ->where('name', $data[0]->name)
            ->first();
        $disc = 0;
        $disc2 = 0;
        foreach ($data as $item) {
            if ($item->discount > 0 || $item->sdis > 0) {
                $disc = $item->discount;
                $disc2 = $item->sdis;
                break;
            }
        }
    @endphp
    <div class="mp-container">
        <div class="right center row">
            @if ($data[0]->mainstatus == 'blue')
                <div class="col s12">
                    <div style="margin: 10px 0;">
                        <a href="{{ url('/user/editorder/' . $data[0]->order_id) }}" class="btn-small amber white-text">
                            Edit
                            <i class="material-icons right">edit</i>
                        </a>
                    </div>
                    <div>
                        <a href="{{ url('/user/deleteorder/' . $data[0]->order_id) }}" class="btn-small red white-text">
                            Delete
                            <i class="material-icons right">delete</i>
                        </a>
                    </div>
                </div>
            @endif
            @if ($data[0]->mainstatus != 'blue')
                <div class="col s12">
                    <div style="margin: 10px 0;">
                        <a href="{{ url('/user/saveorder/' . $data[0]->order_id) }}" target="_blank"
                            class="btn-small green accent-4 white-text">
                            Img <i class="material-icons right">file_download</i>
                        </a>
                    </div>
                    <div>
                        <a href="{{ url('/user/printorder/' . $data[0]->order_id) }}" target="_blank"
                            class="btn-small green accent-4 white-text">
                            PDF <i class="material-icons right">picture_as_pdf</i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div>
            <h6>Customer: {{ $data[0]->name }}</h6>
            <h6>Shop Name: {{ $cus->shopname }}</h6>
            <h6>Order Id: {{ $data[0]->order_id }}</h6>
            <h6>Date: {{ date('Y-m-d', strtotime($data[0]->date)) }}</h6>
            <h6>Miti: {{ getNepaliDate($data[0]->date) }}</h6>
        </div>
        <div class="mp-card" style="overflow-x: scroll">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th class="center">status</th>
                        <th class="center">Quantity</th>
                        <th class="center">Approved Quantity</th>
                        <th class="center">Price</th>
                        <th>total</th>
                        @if($data[0]->mainstatus != "blue")
                            <th>Total(discounted)</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data as $item)
                        <tr>
                            <td @if ($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>
                                {{ $item->item }} @if($item->net != NULL) <span>(NET)</span> @endif
                                <br>
                                <span style="font-size: 5px; margin-top:-10px;">{{ $item->brand }}
                                    {{ $item->category }}</span>
                            </td>
                            <td class="center">{{ $item->status }}</td>
                            <td class="center">{{ $item->quantity }}</td>
                            <td class="center">{{ $item->approvedquantity }}</td>
                            <td class="center">
                                {{ $item->price }}
                            </td>
                            <td>
                                @if ($item->status == 'approved')
                                    {{ $a = $item->approvedquantity * $item->price }}
                                    <span class="hide">{{ $total = $total + $a }}</span>
                                @elseif($item->status == 'pending')
                                    {{ $a = $item->quantity * $item->price }}
                                    <span class="hide">{{ $total = $total + $a }}</span>
                                @else
                                    0
                                @endif
                            </td>
                            @if ($item->mainstatus != "blue")
                            <td>
                                @if ($item->status == 'approved')
                                    {{ $b = $item->approvedquantity * $item->price * (1 - 0.01 * $item->discount) * (1 - 0.01 * $item->sdis) }}
                                    <span class="hide">{{ $total2 = $total2 + $b }}</span>
                                @elseif($item->status == 'pending')
                                    {{ $b = $item->quantity * $item->price * (1 - 0.01 * $item->discount) * (1 - 0.01 * $item->sdis) }}
                                    <span class="hide">{{ $total2 = $total2 + $b }}</span>
                                @else
                                    0
                                @endif
                            </td>
                            @endif
                           
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center" style="font-weight: 700">Total</td>
                        <td style="font-weight: 700">{{ money($total) }}</td>
                    </tr>
                    @if ($disc > 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="center" style="font-weight: 700">(First/cash) Discount</td>
                            <td style="font-weight: 700">{{ $data[0]->discount }}%</td>
                            <td></td>
                        </tr>
                    @endif
                    @if ($disc2 > 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="center" style="font-weight: 700">(Direct/Net) Discount</td>
                            <td style="font-weight: 700">{{ $data[0]->sdis }}%</td>
                            <td></td>
                        </tr>
                    @endif

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center" style="font-weight: 700">Net Total</td>
                        @if ($data[0]->mainstatus != "blue")
                        <td style="font-weight: 700">{{ money($total2) }}</td>
                        <td style="font-weight: 700">{{ money($total2) }}</td>
                        @else
                        <td style="font-weight: 700">{{ money($total) }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
        <form method="post" action="{{ route('user.detailedit') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $data[0]->order_id }}">
            <div class="bg-content mp-card" style="margin-top:30px;">
                <div class="input-field col s12">
                    User Remarks:
                    <textarea name="userremarks" class="browser-default inp textcol" cols="30" rows="10">{{ $data[0]->userremarks }}</textarea>
                </div>
            </div>
            <div class="fixed-action-btn">
                <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})" style="border-radius: 10px;">
                    @if ($data[0]->save == NULL)
                        Send
                    @else
                        Save                        
                    @endif
                    <i class="left material-icons">send</i>
                </button>
            </div>
        </form>

        <div class="mp-card row" style="margin-top: 10px;">
            <div class="col s12">
                Admin Remarks: {{ $data[0]->remarks }}
            </div>
            <div class="col s12">
                Cartoons: {{ $data[0]->cartoons }}
            </div>
            <div class="col s12">
                Transport Detail: {{ $data[0]->transport }}
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        clickButton()

        function clickButton() {
            document.querySelector('.eddl').click();
        }
    </script>
@endsection
