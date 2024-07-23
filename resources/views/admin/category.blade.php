@extends('layouts.admin')

@section('main')
    <div>
        <div class="right">
            <span class="btn green darken-3" onclick="$('#addmodal').modal('open')">Add Category</span>
        </div>
        <div class="center">
            <h4>Category List</h4>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th>SN</th>
                    <th>Category</th>
                </thead>
                <tbody id="cat-tbody">
                    @php
                        $i = 0
                    @endphp
                    @foreach ($data as $item)
                        <tr  oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>{{$i = $i + 1}}</td>
                            <td >{{ $item->category }}</td>
                            <td class="iphone"><a class="modal-trigger btn-flat" href="#menumodal" onclick="changelinkajax('editcat({{$item->id}})','delcat({{$item->id}})')"><i class="material-icons">more_vert</i></a></td>                       
                         </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="editmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Edit Category</h5>
            <form id="editcat">
                <div class="row">
                    <div class="col s12"><label>Category :</label><input id="edcat" name="category" type="text"
                            class="browser-default inp" placeholder="Category" required></div>
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
            <h5 class="center">Add Category</h5>
            <form id="addcat">
                <div class="row">
                    <div class="col s12"><label>Category :</label><input id="adcat" name="category" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
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
            <a id="rmeditlink" onclick="edcat()">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink" onclick="deletecat()">
                <li>Delete</li>
            </a>
        </ul>
    </div>

    <script>
        function edcat(){
            a = $('#rmeditlink').attr('data-value');
            editcat(parseInt(a));
            // console.log(parseInt(a));
        }
        function deletecat(){
            a = $('#rmdeletelink').attr('data-value');
            // deladmin(parseInt(a));
            // console.log(parseInt(a));
            $('#delmodal').modal('open')
            $('#delconfirm').attr('data-value', a)
        }
        function delconfirm(){
            a = $('#delconfirm').attr('data-value');
            delcat(parseInt(a));
            $('#delmodal').modal('close')
            $('#rmdeletelink').removeAttr('data-value');
            $('#delconfirm').removeAttr('data-value');
        }
        function getcatdata(){
            $.ajax({
                url: "/category/getcatdata",
                type: "GET",
                success: function(response){
                    // console.log(response);
                    $('#cat-tbody').text('');
                    i = 0
                    $.each(response, function(key, item){
                        $('#cat-tbody').append(`
                        <tr oncontextmenu="rightmenu(${ item.id }); return false;">
                            <td>${i = i+1}</td>
                            <td>${item.category}</td>
                        </tr>
                        `)
                    })
                }
            })
        }
        function editcat(id) {
            $('#editmodal').modal('open');
            $.ajax({
                url: "/category/getdata/" + id,
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#menumodal').modal('close')
                    $('#edcat').val(response.category)
                    $('#edid').val(response.id)
                }
            })
        }
        $('#editcat').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#editcat")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/category/editcat",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Category Updated!'});
                    getcatdata()
                    $('#editmodal').modal('close');
                    $('#edcat').val('')
                    $('#edid').val('')
                }
            })
        })
        $('#addcat').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#addcat")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/category/addcat",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Category Added!'});
                    getcatdata()
                    $('#addmodal').modal('close');
                    $('#adcat').val('')
                },
                error: function(error){
                    M.toast({html: error.responseJSON.message})
                }
            })
        })
        function delcat(id){
            $.ajax({
                url: "/category/delcat/"+id,
                type: "GET",
                success: function(response){
                    M.toast({html: response});
                    $('#menumodal').modal('close');
                    getcatdata();
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
