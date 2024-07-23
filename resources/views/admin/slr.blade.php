@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <form>
                <div class="row">
                    <div class="col l4 m4 s6">
                        {{-- <label>From:</label> --}}
                        From:
                        <input type="date" name="date" value="{{ $date }}" class="inp browser-default black-text">
                    </div>
                    <div class="col l4 m4 s6">
                        {{-- <label>To:</label> --}}
                        To:
                        <input type="date" name="date2" value="{{ $date2 }}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="input-field col s12 m4 l4" style="margin-top: 20px;">
                        <input type="text" name="name" id="customer" value="{{ $name }}"
                            placeholder="Customer" class="autocomplete browser-default inp black-text" autocomplete="off">
                    </div>
                    <div class="col s6 m2 l2">
                        <button class="btn green accent-4">Apply</button>
                    </div>
                    <div class="col s6 m2 l2">
                        <a class="btn green accent-4" href="{{ url('/slr') }}">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="mp-card" style="margin-top: 20px;">
            <table>
                <thead>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Invoice ID</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr  ondblclick="opendet('{{ $item->returnid }}')"
                            oncontextmenu="rightmenu('{{ $item->returnid }}'); return false;">
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->name.shopname($item->user_id) }}</td>
                            <td>{{ $item->returnid }}</td>
                            <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/editslr/{{$item->returnid}}','/deleteslr/{{$item->returnid}}')"><i class="material-icons">more_vert</i></a></td>
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
        $(document).ready(function() {
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
    </script>
    <script>
        function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/editslr/' + id);
                $('#rmdeletelink').attr('href', '/deleteslr/' + id);

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

        function opendet(id) {
            window.open('/slrdetail/' + id, '_self')
        }
    </script>
@endsection
