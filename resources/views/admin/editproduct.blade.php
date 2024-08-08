@extends('layouts.admin')

@section('main')
    <style>
        .ql-editor {
            color: black !important;
        }

        strong {
            color: black;
            font-weight: 600;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    <div>
        <h5 class="center">Product</h5>
        @error('name')
            <div class="red-text">{{ $message }}</div>
        @enderror
        <div class="mp-card">
            <form action="{{ route('editprod') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col s12">
                        <label>Name :</label><input name="name" value="{{ $prod->name }}" type="text"
                            class="browser-default inp" placeholder="Name" required>
                    </div>
                    <div class="col m6 s12">
                        <div id="input-field">
                            <label>Brand :</label>
                            <select id="MySelct" class=" pop" searchname="myselectsearch" name="brand_id"
                                searchable="Search Brand" required>
                                <option value="" disabled>Select Brand</option>
                                <option value="{{ $prod->brand_id }}" selected>{{ $prod->brand }}</option>
                                @foreach ($brands as $brd)
                                    <option value="{{ $brd->id }}">{{ $brd->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <div id="input-field">
                            <label>Category :</label>
                            <select id="MySelct" class=" pop" searchname="myselectsearch" name="category_id"
                                searchable="Search Category" required>
                                <option value="" disabled>Select Category</option>
                                <option value="{{ $prod->category_id }}" selected>{{ $prod->category }}</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <label>
                            <input type="checkbox" @if ($prod->stock == 'on') checked @endif name="stock" />
                            <span>Out of Stock</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" @if ($prod->hide == 'on') checked @endif name="hide" />
                            <span>Hidden</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" @if ($prod->new == 'on') checked @endif name="new" />
                            <span>New Launch</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" @if ($prod->featured == 'on') checked @endif name="featured" />
                            <span>Featured</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" @if ($prod->trending == 'on') checked @endif name="trending" />
                            <span>Treding</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" @if ($prod->flash == 'on') checked @endif name="flash" />
                            <span>Flash</span>
                        </label>
                        <br>
                        {{-- <label>
                            <input type="checkbox" @if ($prod->net == 'on') checked @endif name="net" />
                            <span>Net</span>
                        </label> --}}
                    </div>
                    <div class="col m6 s12">
                        <label>Price :</label><input name="price" value="{{ $prod->price }}" type="number"
                            class="browser-default inp" placeholder="Price" required>
                    </div>
                    {{-- <div class="col m6 s12">
                        <label> Offer :</label><input name="offer" type="text" value="{{ $prod->offer }}"
                            class="browser-default inp" placeholder="Offer">
                    </div> --}}
                    <div class="col s12" style="margin-top: 20px;">
                        {{-- <label>Details :</label>
                        <textarea name="details" class="browser-default inp" value="{{ $prod->details }}" style="resize: vertical;"
                            placeholder="Details">{{ $prod->details }}</textarea> --}}
                        <div id="editor">
                            {!! $prod->details !!}
                        </div>
                        <input type="hidden" name="details" id="details">
                    </div>
                    <div class="col s12 file-field input-field">
                        <div class="btn">
                            <span>Add Images</span>
                            <input type="file" id="files" multiple name="images[]">
                        </div>
                    </div>
                    <div id="filespreview"></div>
                    @if ($prod->images != '' || $prod->images != null)
                        @foreach (explode('|', $prod->images) as $item)
                            <div class="col m6 s12 valign-wrapper" style="margin-top: 20px;">
                                <img src="{{ asset($item) }}" style="height: 100px;" alt="">
                                <input type="hidden" name="oldimg[]" value="{{ $item }}">
                                <div class="btn" style="margin: 10px;" onclick="$(this).parent().remove()">Remove image
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
                <input type="hidden" name="id" value="{{ $prod->id }}">
                <div class="fixed-action-btn">
                    <button class="btn-large red">
                        Submit <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['blockquote'],
                    [{
                        'align': []
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    // [{ 'direction': 'rtl' }],
                ]
            }
        });

        function addClassToSelectsInToolbar() {
            // Find all 'select' elements within '.ql-toolbar .ql-formats'
            const selects = document.querySelectorAll('.ql-toolbar .ql-formats select');
            const p = document.querySelectorAll('p');
            // const p = document.querySelectorAll('.editor .ql-editor p');

            // Add the 'browser-default' class to each 'select' element
            selects.forEach(select => {
                select.classList.add('browser-default');
            });
            // p.forEach(select => {
            //     select.style.all = "unset";
            // });
        }

        // Call the function to apply the class
        addClassToSelectsInToolbar();
        $(document).ready(function() {
            if (window.File && window.FileList && window.FileReader) {
                $("#files").on("change", function(e) {
                    var files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            $("<div class=\"col s12 m6 valign-wrapper pip\" style='margin-top:20px;'>" +
                                "<img class=\"imageThumb\" src=\"" + e.target.result +
                                "\" title=\"" + file.name + "\" style='height: 200px;'/>" +
                                "</span>").insertAfter("#filespreview");
                            $(".remove").click(function() {
                                $(this).parent(".pip").remove();
                            });

                            // Old code here
                            /*$("<img></img>", {
                              class: "imageThumb",
                              src: e.target.result,
                              title: file.name + " | Click to remove"
                            }).insertAfter("#files").click(function(){$(this).remove();});*/

                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            } else {
                alert("Your browser doesn't support to File API")
            }
            $('form').on('submit', function () {
                // Set the value of the hidden input field to the HTML content of the editor
                $('#details').val(quill.root.innerHTML);
            });
        });
    </script>
@endsection
