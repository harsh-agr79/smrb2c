@extends('layouts/admin')

@section('main')
    @php
        $total = 0;
        $total2 = 0;
        $cus = DB::table('customers')
            ->where('name', $data[0]->name)
            ->first();
    @endphp
    <div>
        <div>
            <h6>Customer: {{ $data[0]->name }}</h6>
            <h6>Shop Name: {{ $cus->shopname }}</h6>
            <h6>Return Id: {{ $data[0]->returnid }}</h6>
            <h6>Date: {{ date('Y-m-d', strtotime($data[0]->date)) }}</h6>
            <h6>Miti: {{ getNepaliDate($data[0]->date) }}</h6>
        </div>
        <form action="{{ route('admin.editslrdet') }}" method="POST">
            <input type="hidden" name="returnid" value="{{ $data[0]->returnid }}">
            @csrf
            <div class="mp-card" style="overflow-x: scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th class="center">Quantity</th>
                            <th class="center">Price</th>
                            <th>total</th>
                            <th>discounted</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $item)
                            <input type="hidden" value="{{ $item->id }}" name="id[]">
                            <tr>
                                <td @if ($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>
                                    {{ $item->item }} @if($item->net != NULL) <span>(NET)</span> @endif
                                <br>
                            <span style="font-size: 5px; margin-top:-10px;">{{$item->brand}} {{$item->category}}</span></td>
                                <td class="center">{{ $item->quantity }}</td>
                                <td class="center">
                                    <span class="green lighten-2 black-text center" style="padding: 2px;"
                                        onclick="this.remove(); $('#{{ $item->id }}').css('display', 'block');">{{ $item->price }}</span>
                                    <input id="{{ $item->id }}" type="text" class="inp browser-default black-text"
                                        style="display: none;" name="price[]" value="{{ $item->price }}">
                                </td>
                                <td>
                                 
                                        {{ money($a = $item->quantity * $item->price) }}
                                        <span class="hide">{{ $total = $total + $a }}</span>
                                   
                                </td>
                                <td>
                                   
                                        {{ money($b = ($item->quantity * $item->price * (1-0.01*$item->discount)) * (1-0.01*$item->sdis))}}
                                        <span class="hide">{{ $total2 = $total2 + $b }}</span>
                                   
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Total</td>
                            <td style="font-weight: 700">{{ $total }}</td>
                            <td style="font-weight: 700">{{ $total2 }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Cash Discount</td>
                            <td><input type="text" name="discount" value="{{ $data[0]->discount }}"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Net Discount</td>
                            <td><input type="text" name="sdis" value="{{ $data[0]->sdis }}"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 700">Net Total</td>
                            <td style="font-weight: 700">{{ $total2}}</td>
                            <td style="font-weight: 700">{{ $total2}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-content mp-card" style="margin-top:30px;">
                <div class="input-field col s12">
                    <textarea id="textarea1" name="remarks" placeholder="Remarks" class="materialize-textarea">{{ $data['0']->remarks }}</textarea>
                </div>
            </div>
            <div class="fixed-action-btn">
                <button class="btn btn-large red" onclick="M.toast({html: 'Order being Updated, Please wait...'})">
                    update SLR
                    <i class="left material-icons">send</i>
                </button>
            </div>
    </div>
    </form>
@endsection
