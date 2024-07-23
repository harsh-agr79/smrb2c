@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <form>
                <div class="row">
                    <div class="col l4 m4 s6">
                        {{-- <label>From:</label> --}}
                        From:
                        <input type="date" name="date" value="{{$date}}" class="inp browser-default black-text">
                    </div>
                    <div class="col l4 m4 s6">
                        {{-- <label>To:</label> --}}
                        To:
                        <input type="date" name="date2" value="{{$date2}}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="input-field col s12 m4 l4" style="margin-top: 20px;">
                        <input type="text" name="name" id="customer" value="{{$name}}"
                            placeholder="Customer" class="autocomplete browser-default inp black-text" autocomplete="off">
                    </div>
                    <div class="col s6 m2 l2">
                        <button class="btn green accent-4">Apply</button>
                    </div>
                    <div class="col s6 m2 l2">
                        <a class="btn green accent-4" href="{{ url('/payments') }}">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="center">
            {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <table class="sortable">
                <thead>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Id</th>
                    <th>Amount</th>
                    <th>Entry By</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->paymentid }}); return false;">
                            <td>{{getNepaliDate($item->date)}}</td>
                            <td>{{ $item->name.shopname($item->user_id) }}</td>
                            <td>{{$item->type}}</td>
                            <td><a href="{{url('/editpayment/'.$item->paymentid)}}">{{$item->paymentid}}</a></td>
                            <td>{{$item->amount}}</td>
                            <td>{{$item->entry_by}}</td>
                            <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editpayment/{{$item->paymentid}}','/deletepayment/{{$item->paymentid}}')"><i class="material-icons">more_vert</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="center">
            {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
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
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/findcustomer',
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
    </script>
     <script>
         function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/editpayment/' + id);
                $('#rmdeletelink').attr('href', '/deletepayment/' + id);
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
 </script>
@endsection