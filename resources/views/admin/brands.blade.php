@extends('layouts.admin')

@section('main')
    <div>
        <div class="right">
            <span class="btn green darken-3" onclick="$('#addmodal').modal('open')">Add Brand</span>
        </div>
        <div class="center">
            <h4>Brands List</h4>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th>logo</th>
                    <th>Name</th>
                    <th>info</th>
                </thead>
                <tbody id="brand-tbody">
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>
                                @if ($item->logo == NULL)
                                <img src="{{asset('images/user.png')}}" class="materialboxed table-dp">
                                @else
                                <img src="{{asset($item->logo)}}" class="materialboxed table-dp">
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->info }}</td>
                            <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelinkajax('editbrand({{$item->id}})','delbrand({{$item->id}})')"><i class="material-icons">more_vert</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="editmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Edit Details of: <span id="edtitle"></span></h5>
            <form id="editbrand">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="edname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Info :</label><input id="edinfo" name="info" type="text"
                            class="browser-default inp" placeholder="Info" required></div>
                    <div class="col s12" style="margin-top: 10px;">
                        <img src="" id="edlogo" style="height: 100px;" alt="">
                    </div>
                    <div class="col s12 file-field input-field">
                        <div class="btn">
                            <span>Logo</span>
                            <input type="file" id="adlogo" name="logo" onchange="edloadFile(event)">
                        </div>
                    </div>
                    <input type="hidden" name="id" id="edid">
                    <input type="hidden" name="oldlogo" id="edoldlogo">
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="addmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Add Brand</h5>
            <form id="addbrand" enctype="multipart/form-data">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="adname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Info :</label><input id="adinfo" name="info" type="text"
                            class="browser-default inp" placeholder="Info" required></div>
                    <div class="col s12" style="margin-top: 10px;">
                        <img src="" id="adlogopre" style="height: 100px;" alt="">
                    </div>
                    <div class="col s12 file-field input-field">
                        <div class="btn">
                            <span>Logo</span>
                            <input type="file" id="adlogo" name="logo" onchange="loadFile(event)">
                        </div>
                    </div>
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="delmodal" class="modal">
        <div class="modal-content center">
            <h5>Are you you sure you want to delete?</h5>
            <div class="row">
                <div class="col s6 center">
                    <button class="btn red darken-3" id="delconfirm" onclick="delconfirm()">Confirm</button>
                </div>
                <div class="col s6 center">
                    <button class="btn green darken-3" onclick="$('#delmodal').modal('close')">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink" onclick="edbrand()">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink" onclick="deletebrand()">
                <li>Delete</li>
            </a>
        </ul>
    </div>

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('adlogopre');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
        var edloadFile = function(event) {
            var output = document.getElementById('edlogo');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        function edbrand() {
            a = $('#rmeditlink').attr('data-value');
            editbrand(parseInt(a));
            // console.log(parseInt(a));
        }

        function deletebrand() {
            a = $('#rmdeletelink').attr('data-value');
            // deladmin(parseInt(a));
            // console.log(parseInt(a));
            $('#delmodal').modal('open')
            $('#delconfirm').attr('data-value', a)
        }

        function delconfirm() {
            a = $('#delconfirm').attr('data-value');
            delbrand(parseInt(a));
            $('#delmodal').modal('close')
            $('#rmdeletelink').removeAttr('data-value');
            $('#delconfirm').removeAttr('data-value');
        }

        function getbranddata() {
            $.ajax({
                url: "/brand/getbranddata",
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#brand-tbody').text('');
                    $.each(response, function(key, item) {
                        if(item.logo == null){
                            image = 'images/user.png'
                        }
                        else{
                            image = item.logo
                        }
                        $('#brand-tbody').append(`
                        <tr oncontextmenu="rightmenu(${ item.id }); return false;">
                            <td><img src="${image}" class="materialboxed table-dp"></td>
                            <td>${item.name}</td>
                            <td>${item.info}</td>
                        </tr>
                        `)
                    })
                }
            })
        }

        function editbrand(id) {
            $('#editmodal').modal('open');
            $.ajax({
                url: "/brand/getdata/" + id,
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#menumodal').modal('close');
                    $('#edname').val(response.name)
                    $('#edinfo').val(response.info)
                    $('#edlogo').attr('src', response.logo)
                    $('#edid').val(response.id)
                }
            })
        }
        $('#editbrand').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#editbrand")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/brand/editbrand",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({
                        html: 'Brand Updated!'
                    });
                    getbranddata()
                    $('#editmodal').modal('close');
                    $('#edname').val('')
                    $('#edinfo').text('')
                    $('#edid').val('')
                }
            })
        })
        $('#addbrand').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#addbrand")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/brand/addbrand",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({
                        html: 'Brand Added!'
                    });
                    getbranddata()
                    $('#addmodal').modal('close');
                    $('#adname').val('')
                    $('#adinfo').val('')
                },
                error: function(error) {
                    M.toast({
                        html: error.responseJSON.message
                    })
                }
            })
        })

        function delbrand(id) {
            $.ajax({
                url: "/brand/delbrand/" + id,
                type: "GET",
                success: function(response) {
                    $('#menumodal').modal('close');
                    M.toast({
                        html: response
                    });
                    getbranddata();
                }
            })
        }

        function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'block';
            rmenu.style.top = mouseY(event) + 'px';
            rmenu.style.left = mouseX(event) + 'px';
            $('#rmeditlink').attr('data-value', id);
            $('#rmdeletelink').attr('data-value', id);
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
