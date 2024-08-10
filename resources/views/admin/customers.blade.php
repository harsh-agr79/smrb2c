@extends('layouts.admin')

@section('main')
<style>
    .table-dp{
        height: 60px;
        border-radius: 50%;
    }
</style>
    <div>
        <h5 class="center">Customers List</h5>
        <div class='input-field col s6'>
            <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                autocomplete="off" type='search' name='search' id='search' />
            <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
        </div>
        <span class="red-text center"
        style="font-size: 20px; font-weight:500; text-align:center;">{{ session('error') }}</span>
        <div class="mp-card" style="overflow-x: scroll;">
            <table>
                <thead>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Email</th>
                    {{-- <th>Address</th> --}}
                    {{-- <th>Contact</th> --}}
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>{{$a = $a + 1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            {{-- <td>{{$item->userid}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{$item->contact}}</td>
                        <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelink('/customers/edit/{{$item->id}}','/customers/delcust/{{$item->id}}')"><i class="material-icons">more_vert</i></a></td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li>Delete</li>
            </a>
        </ul>
    </div> --}}
    <script>
         $(document).ready(function(){
  $('.modal').modal();

});
         function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', "/customers/edit/"+id);
                $('#rmdeletelink').attr('href', "/customers/delcust/"+id);
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
    </script>
@endsection