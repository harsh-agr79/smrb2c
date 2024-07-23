@extends('layouts/admin')

@section('main')
@php
    $dis = ''
@endphp
<div class="center">
    {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
</div>
    <div class="mp-card" style="overflow-x: scroll; margin-top: 30px;">
        <h6 class="center">Delivered Orders</h6>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Detail</th>

                        <th class="tamt" style="display: none;">Amount</th>
                    
                    <th>Deliver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr id="{{$item->order_id}}tr" class=" @if ($item->seen == '') z-depth-2 @endif"
                        oncontextmenu="rightmenu({{ $item->order_id }}); return false;"
                        ondblclick="opendetail({{ $item->order_id }}, '{{ $item->seen }}')">
                        <td>
                            <div id="{{ $item->order_id . 'order' }}" class="{{$stat = $item->mainstatus}}"
                                style="height: 35px; width:10px;"></div>
                        </td>
                        <td>{{ getNepaliDate($item->date) }}</td>
                        <td>
                            <div class="row" style="padding: 0; margin: 0;">
                                <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name.shopname($item->user_id) }}</div>
                                <div class="col s4 m4 l3" style="font-size: 7px;">{{ $item->order_id }}</div>
                                <div class="col s8 m8 l4" style="font-size: 10px;">{{ $item->marketer }}</div>
                            </div>
                        </td>
                        
                            <td class="tamt" style="display: none;">
                                {{ getTotalAmount($item->order_id) }}
                            </td>
                        
                        <td>
                            <form id="{{$item->order_id}}deliverform">
                            <label>
                                <input type="hidden" name="order_id" value="{{ $item->order_id }}">
                                <input id="{{ $item->order_id.'deliver' }}" {{$dis}} type="checkbox" name="delivered"
                                   @if ($stat == 'green')
                                    checked
                                   @endif onchange="updatedeliver({{$item->order_id}})" />
                                <span id="{{ $item->order_id . 'deliverspan' }}">@if ($stat == 'green')
                                    Delivered
                                   @endif</span>
                            </label>
                           
                            </form>
                        </td>
                        <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editorder/{{$item->order_id}}','/')"><i class="material-icons">more_vert</i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="center">
        {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
    </div>
    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            @if ($admin->type == 'admin')
            <a id="rmdeletelink">
                <li class="border-top">Delete</li>
            </a>
            @endif
        </ul>
    </div>
    <script>
        function rightmenu(order_id) {
            // console.log(order_id)
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

        function opendetail(order_id, seen) {
            
                    window.open('/detail/' + order_id, "_self");
                
        }
    </script>
    <script>
        function updatedeliver(order_id) {
            var admintype = `admin`;
            if (admintype == "admin") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/updatedeliver",
                    data: $(`#${order_id}deliverform`).serialize(),
                    type: 'post',
                    success: function(response) {
                        if (!response.hasOwnProperty('delivered')) {
                        $(`#${response.order_id}tr`).remove();
                        }
                    }
                })
            }
        }
    </script>
@endsection
