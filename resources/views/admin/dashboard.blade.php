@extends('layouts/admin')

@section('main')
    <div>
        <div>
            <h5 class="center">Dashboard</h5>
        </div>
            @if ($admin->type != 'staff')
            <label>
                <input type="checkbox" onclick="toggleamt()" />
                <span>View Total Amount</span>
            </label>
            @endif
           
        
        <div class="row" >
            <div class="col l6 s12" style="margin-top: 15px; padding: 1px;">
                <div class="mp-card" style="overflow-x: scroll">
                    <h6 class="center">All Orders</h6>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Seen By</th>
                                
                                    <th class="tamt" style="display: none;">Amount</th>
                                
                                    <th>Pack Order</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mpe as $item)
                                <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                    oncontextmenu="rightmenu({{ $item->order_id }}); return false;"
                                    ondblclick="opendetail({{ $item->order_id }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                    <td>
                                        <div id="{{ $item->order_id . 'order' }}" class="{{ $stat = $item->mainstatus }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDay($item->date) }}-{{ getNepaliMonth($item->date) }}
                                        {{ date('H:i', strtotime($item->date)) }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name.shopname($item->user_id) }}</div>
                                            <div class="col s4 m4 l3" style="font-size: 7px;">{{ $item->order_id }}</div>
                                            <div class="col s8 m8 l4" style="font-size: 10px;">{{ $item->marketer }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->seenby }}</td>
                                    
                                        <td class="tamt" style="display: none;">
                                            {{ getTotalAmount($item->order_id) }}
                                        </td>
                                    
                                        <td class="center">
                                            <form id="{{ $item->order_id }}">
                                                <input type="hidden" name="order_id" value="{{ $item->order_id }}">
                                                <label>
                                                    <input type="checkbox" value="packorder" name="packorder"
                                                        @if ($stat == 'blue' || $stat == 'red') disabled
                                                @elseif($stat == 'amber darken-1')
                                                @elseif($stat == 'green')
                                                checked disabled
                                                @elseif($stat == 'deep-purple')
                                                checked @endif
                                                        onclick="updatecln({{ $item->order_id }})" />
                                                    <span></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editorder/{{$item->order_id}}','/deleteorder/{{$item->order_id}}')"><i class="material-icons">more_vert</i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col l6 s12" style="margin-top: 15px; padding: 1px;">
                <div class="mp-card" style="overflow-x: scroll">
                    <h6 class="center">Pending Orders</h6>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Seen By</th>
                                
                                    <th class="tamt" style="display: none;">Amount</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending as $item)
                                <tr class="@if ($item->seen == '') z-depth-2 @endif"
                                    oncontextmenu="rightmenu({{ $item->order_id }}); return false;"
                                    ondblclick="opendetail({{ $item->order_id }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                    <td>
                                        <div id="{{ $item->order_id . 'order' }}" class="{{ $item->mainstatus }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDate($item->date) }}
                                        {{ date('H:i', strtotime($item->date)) }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name.shopname($item->user_id) }}</div>
                                            <div class="col s4 m3 l3" style="font-size: 8px;">{{ $item->order_id }}</div>
                                            <div class="col s6 m4 l4" style="font-size: 8px;">{{ $item->marketer }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->seenby }}</td>
                                    <td class="tamt" style="display: none;"> {{ getTotalAmount($item->order_id) }}</td>
                                    <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editorder/{{$item->order_id}}','/deleteorder/{{$item->order_id}}')"><i class="material-icons">more_vert</i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
        function toggleamt() {
            $('.tamt').toggle();
        }
        $('tr').on('taphold', function(e){
            console.log(e);
        })
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

        function opendetail(order_id, seen ,ms) {
            
                    window.open('/detail/' + order_id, "_self");
        }
    </script>
    <script>
        function updatecln(order_id) {
           
            var admintype = `admin`;
            if (admintype == "admin") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },  
                    url: "/updatecln",
                    data: $(`#${order_id}`).serialize(),
                    type: 'post',
                    success: function(response) {
                        if (response.hasOwnProperty('packorder')) {
                            $(`#${response.order_id}order`).removeAttr('class');
                            $(`#${response.order_id}order`).addClass("deep-purple");
                        } else {
                            $(`#${response.order_id}order`).removeAttr('class');
                            $(`#${response.order_id}order`).addClass("amber darken-1");
                        }

                    }
                })
            }
        }
    </script>
@endsection
