@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="overflow-x: scroll; margin-top: 30px;">
            <div>
                <h5 class="center">Rejected Orders</h5>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>order Id</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                            oncontextmenu="rightmenu({{ $item->order_id }}); return false;"
                            ondblclick="opendetail({{ $item->order_id }}, '{{$item->seen}}')">
                            <td>
                                <div id="{{ $item->order_id . 'order' }}" class="{{$item->mainstatus}}"
                                    style="height: 35px; width:10px;"></div>
                            </td>
                            <td>{{ getNepaliDate($item->date) }}</td>
                            <td>{{ $item->name.shopname($item->user_id) }}</td>
                            <td>{{ $item->order_id }}</td>
                            <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editorder/{{$item->order_id}}','/deleteorder/{{$item->order_id}}')"><i class="material-icons">more_vert</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
@endsection