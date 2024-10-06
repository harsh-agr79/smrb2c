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
                    <th>Image</th>
                    <th>Actions</th>
                </thead>
                <tbody id="cat-tbody">
                    @php
                        $i = 0
                    @endphp
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>{{ $i = $i + 1 }}</td>
                            <td>{{ $item->category }}</td>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('categories/' . $item->image) }}" alt="{{ $item->category }}" width="50" height="50">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="iphone">
                                <a class="modal-trigger btn-flat" href="#menumodal" onclick="changelinkajax('editcat({{ $item->id }})','delcat({{ $item->id }})')">
                                    <i class="material-icons">more_vert</i>
                                </a>
                            </td>                       
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Edit Category</h5>
            <form id="editcat" enctype="multipart/form-data">
                <div class="row">
                    <div class="col s12">
                        <label>Category :</label>
                        <input id="edcat" name="category" type="text" class="browser-default inp" placeholder="Category" required>
                    </div>
                    <div class="col s12">
                        <label>Image (PNG only):</label>
                        <input id="edimage" name="image" type="file" accept="image/png" class="browser-default inp">
                        <small>Leave blank to keep existing image.</small>
                    </div>
                    <input type="hidden" name="id" id="edid">
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Add Category</h5>
            <form id="addcat" enctype="multipart/form-data">
                <div class="row">
                    <div class="col s12">
                        <label>Category :</label>
                        <input id="adcat" name="category" type="text" class="browser-default inp" placeholder="Name" required>
                    </div>
                    <div class="col s12">
                        <label>Image (PNG only):</label>
                        <input id="adimage" name="image" type="file" accept="image/png" class="browser-default inp" required>
                    </div>
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delmodal" class="modal">
        <div class="modal-content center">
            <h5>Are you sure you want to delete?</h5>
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

    <!-- Right Click Menu -->
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
        let currentCategoryId = null; // To keep track of the category being edited or deleted

        function edcat(){
            currentCategoryId = $('#rmeditlink').attr('data-value');
            editcat(parseInt(currentCategoryId));
        }

        function deletecat(){
            currentCategoryId = $('#rmdeletelink').attr('data-value');
            $('#delmodal').modal('open')
            $('#delconfirm').attr('data-value', currentCategoryId)
        }

        function delconfirm(){
            const id = $('#delconfirm').attr('data-value');
            delcat(parseInt(id));
            $('#delmodal').modal('close')
            $('#rmdeletelink').removeAttr('data-value');
            $('#delconfirm').removeAttr('data-value');
        }

        function getcatdata(){
            $.ajax({
                url: "/category/getcatdata",
                type: "GET",
                success: function(response){
                    $('#cat-tbody').empty();
                    let i = 0;
                    $.each(response, function(key, item){
                        $('#cat-tbody').append(`
                            <tr oncontextmenu="rightmenu(${ item.id }); return false;">
                                <td>${++i}</td>
                                <td>${item.category}</td>
                                <td>
                                    ${item.image ? `<img src="/categories/${item.image}" alt="${item.category}" width="50" height="50">` : 'N/A'}
                                </td>
                                <td class="iphone">
                                    <a class="modal-trigger btn-flat" href="#menumodal" onclick="changelinkajax('editcat(${item.id})','delcat(${item.id})')">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                </td>                       
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
                    $('#menumodal').modal('close')
                    $('#edcat').val(response.category)
                    $('#edid').val(response.id)
                    // Optionally display current image
                }
            })
        }

        $('#editcat').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
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
                    $('#edimage').val('')
                },
                error: function(error){
                    M.toast({html: error.responseJSON.message})
                }
            })
        })

        $('#addcat').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
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
                    $('#adimage').val('')
                },
                error: function(error){
                    let message = 'An error occurred';
                    if (error.responseJSON && error.responseJSON.errors) {
                        message = Object.values(error.responseJSON.errors).flat().join('<br>');
                    } else if (error.responseJSON && error.responseJSON.message) {
                        message = error.responseJSON.message;
                    }
                    M.toast({html: message})
                }
            })
        })

        function delcat(id){
            $.ajax({
                url: "/category/delcat/" + id,
                type: "GET",
                success: function(response){
                    M.toast({html: response});
                    $('#menumodal').modal('close');
                    getcatdata();
                },
                error: function(error){
                    M.toast({html: 'Failed to delete category.'});
                }
            })
        }

        function rightmenu(id) {
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

        // Initialize modals (assuming you're using Materialize CSS)
        $(document).ready(function(){
            $('.modal').modal();
        });
    </script>
@endsection
