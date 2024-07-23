@extends('layouts.admin')

@section('main')
    <div>
        <div class="right">
            <span class="btn green darken-3" onclick="$('#addmodal').modal('open')">Add Admin</span>
        </div>
        <div class="center">
            <h4>Admins List</h4>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Id</th>
                    <th>Type</th>
                </thead>
                <tbody id="admin-tbody">
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td >{{ $item->name }}</td>
                            <td >{{ $item->email }}</td>
                            <td >{{ $item->userid }}</td>
                            <td >{{ $item->type }}</td>
                            <td class="iphone"><a data-target="drop{{ $item->id }}" class="dropdown-trigger btn-flat"><i class="material-icons">more_vert</i></a></td>
                        </tr>
                        <ul id='drop{{ $item->id }}' class='dropdown-content iphone'>
                            <li><a onclick="editadmin({{$item->id}})">Edit</a></li>
                            <li><a onclick="deladmin({{$item->id}})">Delete</a></li>
                        </ul>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="editmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Edit Details of: <span id="edtitle"></span></h5>
            <form id="editadmin">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="edname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Email :</label><input id="edemail" name="email" type="text"
                            class="browser-default inp" placeholder="Email" required></div>
                    <div class="col s12"><label>User ID :</label><input id="eduserid" name="userid" type="text"
                            class="browser-default inp" placeholder="User ID" required></div>
                    <div class="col s12"><label>Password :</label><input id="edpassword" name="password" type="password"
                            class="browser-default inp" placeholder="Edit Password"></div>
                    <input type="hidden" name="id" id="edid">
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="addmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Add Admin</h5>
            <form id="addadmin">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="adname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Email :</label><input id="ademail" name="email" type="text"
                            class="browser-default inp" placeholder="Email" required></div>
                    <div class="col s12"><label>User ID :</label><input id="aduserid" name="userid" type="text"
                            class="browser-default inp" placeholder="User ID" required></div>
                    <div class="col s12"><label>Password :</label><input id="adpassword" name="password" type="password"
                            class="browser-default inp" placeholder="Create Password" required></div>
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
            <a id="rmeditlink" onclick="edadmin()">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink" onclick="deleteadmin()">
                <li>Delete</li>
            </a>
        </ul>
    </div>

    <script>
        function edadmin(){
            a = $('#rmeditlink').attr('data-value');
            editadmin(parseInt(a));
            // console.log(parseInt(a));
        }
        function deleteadmin(){
            a = $('#rmdeletelink').attr('data-value');
            // deladmin(parseInt(a));
            // console.log(parseInt(a));
            $('#delmodal').modal('open')
            $('#delconfirm').attr('data-value', a)
        }
        function delconfirm(){
            a = $('#delconfirm').attr('data-value');
            deladmin(parseInt(a));
            $('#delmodal').modal('close')
            $('#rmdeletelink').removeAttr('data-value');
            $('#delconfirm').removeAttr('data-value');
        }
        function getadmindata(){
            $.ajax({
                url: "/admin/getadmindata",
                type: "GET",
                success: function(response){
                    // console.log(response);
                    $('#admin-tbody').text('');
                    $.each(response, function(key, item){
                        $('#admin-tbody').append(`
                        <tr oncontextmenu="rightmenu(${ item.id }); return false;">
                            <td>${item.name}</td>
                            <td>${item.email}</td>
                            <td>${item.userid}</td>
                            <td>${item.type}</td>
                        </tr>
                        `)
                    })
                }
            })
        }
        function editadmin(id) {
            $('#editmodal').modal('open');
            $.ajax({
                url: "/admin/getdata/" + id,
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#edname').val(response.name)
                    $('#edtitle').text(response.name)
                    $('#edemail').val(response.email)
                    $('#eduserid').val(response.userid)
                    $('#edid').val(response.id)
                }
            })
        }
        $('#editadmin').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#editadmin")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/admin/editadmin",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Admin Updated!'});
                    getadmindata()
                    $('#editmodal').modal('close');
                    $('#edname').val('')
                    $('#edtitle').text('')
                    $('#edemail').val('')
                    $('#eduserid').val('')
                    $('#edpassword').val('')
                    $('#edid').val('')
                }
            })
        })
        $('#addadmin').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#addadmin")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/admin/addadmin",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Admin Added!'});
                    getadmindata()
                    $('#addmodal').modal('close');
                    $('#adname').val('')
                    $('#ademail').val('')
                    $('#aduserid').val('')
                    $('#adpassword').val('')
                },
                error: function(error){
                    M.toast({html: error.responseJSON.message})
                }
            })
        })
        function deladmin(id){
            $.ajax({
                url: "/admin/deladmin/"+id,
                type: "GET",
                success: function(response){
                    M.toast({html: response});
                    getadmindata();
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
