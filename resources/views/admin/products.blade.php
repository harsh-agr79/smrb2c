@extends('layouts.admin')

@section('main')
<style>
    .table-dp{
        height: 60px;
        border-radius: 50%;
    }
</style>
    <div>
        
        <div class='input-field col s6'>
            <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                autocomplete="off" type='search' name='search' id='search' />
            <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
        </div>
        <h5 class="center">Products List</h5>

        <div class="mp-card" style="overflow-x: scroll;">
            <table>
                <thead>
                    {{-- <th>SN</th> --}}
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Out of Stock</th>
                    <th>Hidden</th>
                    <th>New Launch</th>
                    <th>Price</th>
                </thead>
                <tbody class="row_position">
                    <form id="arrangement" method="POST">
                        @csrf
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                        <tr   oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            {{-- <td>{{$a = $a + 1}}</td> --}}
                            <td>{{$item->name}}</td>
                            <td>{{$item->brand}}</td>
                            <td>{{$item->category}}</td>
                            <td>{{$item->stock}}</td>
                            <td>{{$item->hide}}</td>
                            <td>{{$item->featured}}</td>
                            <td>{{$item->price}}</td>
                        <input type="hidden" class="prodids" name="id[]" value="{{$item->id}}">
                        <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/products/edit/{{$item->id}}','/products/delprod/{{$item->id}}')"><i class="material-icons">more_vert</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
                </form>
            </table>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li>Delete</li>
            </a>
        </ul>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $(".row_position>tr").each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder();
                // console.log("hello")
            },
            // change: function() {
            //     console.log('hello');
            // }
        });
    </script>
    <script>
         function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', "/products/edit/"+id);
                $('#rmdeletelink').attr('href', "/products/delprod/"+id);
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
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let table = document.getElementsByTagName('table');
            let tr = $('tr')
            clsBtn.addEventListener("click", function() {
                a.value = '';
                a.focus();
                var filter = '';
                for (var i = 0; i < tr.length; i++) {
                    tr[i].style.display = "";
                }
                $('#cs-icon').text('search')
            });
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }

            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
        }
        function updateOrder(){
            var prodid = $('.prodids')
            prod = []
            for (let i = 0; i < prodid.length; i++) {
                prod.push(parseInt(prodid[i].value))
            }
            // console.log(prod);
            var formdata = new FormData()
            formdata.append('prod', prod)
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/product/updatearrangement",
                data: formdata,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    // console.log(response)
                    // gettotal();
                }
            })
        }
    </script>
@endsection