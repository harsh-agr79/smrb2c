@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 10px;">
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
        <div class="mp-card">
            <table>
                <thead>
                    <th></th>
                    <th>Name</th>
                    <th>Order id</th>
                    <th><label>
                        <input type="checkbox" id="selectall" onchange="toggleall()"/>
                        <span style="font-size: 10px;">Select All</span>
                      </label></th>
                </thead>
                <form action="{{route('bulkprint')}}" method="POST">
                    @csrf
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <div id="{{ $item->order_id . 'order' }}" class="{{$item->mainstatus}}"
                                    style="height: 35px; width:10px;"></div>
                            <td>{{$item->name}}</td>
                            <td>{{$item->order_id}}</td>
                            <td><label>
                                <input type="checkbox" class="bprint" name="order_id[]" value="{{$item->order_id}}" />
                                <span></span>
                              </label></td>
                        </tr>
                    @endforeach
                </tbody>
                <div class="fixed-action-btn">
                    <button class="btn btn-large red">
                        Print Orders
                      <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
            </table>
        </div>
        <div class="center">
            {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
        </div>
    </div>
    <script>
        function toggleall(){
            if($('#selectall').is(':checked')){
                $('.bprint').attr('checked', 'true')
            }
            else{
                $('.bprint').removeAttr('checked')
            }
        }
    </script>
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
@endsection