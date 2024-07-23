@extends('layouts/admin')

@section('main')
    <div>
        <div class="row">
            <div class="col s4">
                <div class="valign-wrapper">
                    <div class="blue" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Pending</span>
                </div>
                <div class="valign-wrapper">
                    <div class="amber darken-1" style="width: 10px; height:10px; margin-right: 5px;"></div>
                    <span>Approved</span>
                </div>
                <div class="valign-wrapper">
                    <div class="deep-purple" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Packing
                        Order</span>
                </div>
            </div>
            <div class="col s4">
                <h5 class="center">All Orders</h5>
            </div>
            <div class="col s4">
                <div class="valign-wrapper">
                    <div class="red" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Rejected</span>
                </div>
                <div class="valign-wrapper">
                    <div class="green" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Delivered</span>
                </div>
            </div>
        </div>
        <div class="mp-card">
            <form id="filter">
                <div class="row">
                    <div class="row col s12">
                        <div class="input-field col s12 m4">
                            <input type="date" name="date" value="{{ $date }}"
                                class="browser-default inp black-text">
                        </div>
                        <div class="input-field col s12 m4">
                            <input type="date" name="date2" value="{{ $date2 }}"
                                class="browser-default inp black-text">
                        </div>
                        <div class="input-field col s12 m4 l4">
                            <input type="text" name="name" id="customer" value="{{ $name }}"
                                placeholder="Customer" class="autocomplete browser-default inp black-text"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="row col s12">
                        <div class="input-field col s12 l4 m4">
                            <input type="text" name="product" id="product" value="{{ $product }}"
                                placeholder="Product" class="autocomplete browser-default inp black-text"
                                autocomplete="off">
                        </div>
                        <div class="input-field col s12 m4 l4">
                            <select name="status" class="browser-default selectinp">
                                @if ($status != '')
                                    <option value="{{ $status }}" selected>{{ $status }}</option>
                                    <option value="">Item Status</option>
                                @else
                                    <option value="" selected disabled>Item Status</option>
                                @endif
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                        <div class="col m4 s12" style="margin-top: 20px">
                            <button class="btn green accent-4">Apply</button>
                            <a class="btn green accent-4" href="{{ url('orders') }}">clear</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div>
            <div class="center">
                {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
            </div>
            <div class="mp-card" style="overflow-x: scroll;">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Detail</th>
                            @if ($product != '')
                                <th>Quantity</th>
                            @endif
                            <th>Delivered</th>
                            <th>recieved</th>
                            <th class="tamt" style="display: none;">Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr  class="@if ($item->seen == '') z-depth-2 @endif"
                                oncontextmenu="rightmenu({{ $item->order_id }}); return false;"
                                ondblclick="opendetail({{ $item->order_id }}, '{{ $item->seen }}', '{{ $item->mainstatus }}')">
                                <td>
                                    <div id="{{ $item->order_id . 'order' }}" class="{{ $item->mainstatus }}"
                                        style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ getNepaliDate($item->date) }}</td>
                                <td>
                                    <div class="row" style="padding: 0; margin: 0;">
                                        <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name.shopname($item->user_id) }}
                                        </div>
                                        <div class="col s4 m4 l3" style="font-size: 7px;">{{ $item->order_id }}</div>
                                        <div class="col s8 m8 l4" style="font-size: 10px;">{{ $item->marketer }}</div>
                                    </div>
                                </td>
                                @if ($product != '')
                                    <td>{{ $item->quantity }}</td>
                                @endif
                                <td>
                                    @if ($item->delivered == 'on')
                                        <i class="material-icons textcol">check</i>
                                    @else
                                        <i class="material-icons textcol">close</i>
                                    @endif
                                </td>
                                <td>{{ $item->receiveddate }}</td>
                                <td class="tamt" style="display: none;"> {{ getTotalAmount($item->order_id) }}</td>
                                <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editorder/{{$item->order_id}}','/deleteorder/{{$item->order_id}}')"><i class="material-icons">more_vert</i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="center">
                {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
            </div>
        </div>
    </div>
    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li class="border-top">Delete</li>
            </a>
        </ul>
    </div>

    <script>
        function rightmenu(order_id) {
            console.log(order_id)
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'block';
            rmenu.style.top = mouseY(event) + 'px';
            rmenu.style.left = mouseX(event) + 'px';
            $('#rmeditlink').attr('href', '/editorder/' + order_id);
            $('#rmdeletelink').attr('href', '/deleteorder/' + order_id);
        }

        $(document).bind("click", function(event) {
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'none';
        });

        function mouseX(evt) {
            if (evt.pageX) {
                return evt.pageX;
            } else if (evt.clientX) {
                return evt.clientX + (document.documentElement.scrollLeft ?
                    document.documentElement.scrollLeft :
                    document.body.scrollLeft);
            } else {
                return null;
            }
        }

        // Set Top Style Proparty
        function mouseY(evt) {
            if (evt.pageY) {
                return evt.pageY;
            } else if (evt.clientY) {
                return evt.clientY + (document.documentElement.scrollTop ?
                    document.documentElement.scrollTop :
                    document.body.scrollTop);
            } else {
                return null;
            }
        }

        function opendetail(order_id, seen, ms) {
            window.open('/detail/' + order_id, "_self");
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.dropdown-trigger').dropdown({
                coverTrigger: false,
                constrainWidth: false,
            });
            $.ajax({
                type: 'get',
                url: '{!! URL::to('findcustomer') !!}',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '{!! URL::to('finditem') !!}',
                success: function(response) {

                    var custarray = response;
                    var datacust = {};
                    for (var i = 0; i < custarray.length; i++) {

                        datacust[custarray[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#product').autocomplete({
                        data: datacust,
                    });
                }
            })
        })
    </script>
@endsection
