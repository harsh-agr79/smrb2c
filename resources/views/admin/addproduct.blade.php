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
            <form action="{{ route('addprod') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col s12">
                        <label>Name :</label><input name="name" type="text" class="browser-default inp"
                            placeholder="Name" required>
                    </div>
                    <div class="col m6 s12">
                        <div id="input-field">
                            <label>Brand :</label>
                            <select id="MySelct" class=" pop" searchname="myselectsearch" name="brand_id"
                                searchable="Search Brand" required>
                                <option value="" disabled selected>Select Brand</option>
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
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <label>
                            <input type="checkbox" name="stock"/>
                            <span>Out of Stock</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="hide"/>
                            <span>Hidden</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="new" />
                            <span>New Launch</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="featured" />
                            <span>Featured</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="trending" />
                            <span>Treding</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="flash" />
                            <span>Flash</span>
                        </label>
                        <br>
                    </div>
                    <div class="col m6 s12">
                        <label>Price :</label><input name="price" type="number" class="browser-default inp"
                            placeholder="Price" required>
                    </div>
                    <div class="col m6 s12">
                        <label> Discount :</label><input name="offer" type="text" class="browser-default inp"
                            placeholder="Offer">
                    </div>
                    <div class="col s12">
                        <label>Product Variations:</label>
                        <div id="variations-container">
                            <!-- Variation template -->
                        </div>
                        <button type="button" id="add-variation" class="btn">Add Variation</button>
                    </div>
                    <div class="col s12" style="margin-top: 20px;">
                        <div id="editor">
                          
                        </div>
                        <input type="hidden" name="details" id="details">
                    </div>
                    <div class="col s12 file-field input-field">
                        <div class="btn">
                            <span>Select Images</span>
                            <input type="file" id="files" multiple name="images[]">
                        </div>
                    </div>
                    <div id="filespreview"></div>
                </div>
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
                                "\" title=\"" + file.name + "\" style='height: 200px;'/>"+
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

        let variationIndex = 0;
    document.getElementById('add-variation').addEventListener('click', function () {
        const container = document.getElementById('variations-container');
        const newVariation = `
            <div class="variation-row">
                <div class="col s12 m3">
                    <label>Specification 1:</label>
                    <input name="variations[${variationIndex}][specification_1]" type="text" class="browser-default inp" placeholder="Specification 1" required>
                </div>
                <div class="col s12 m3">
                    <label>Specification 2:</label>
                    <input name="variations[${variationIndex}][specification_2]" type="text" class="browser-default inp" placeholder="Specification 2" required>
                </div>
                <div class="col s12 m3">
                    <label>Colors (comma-separated):</label>
                    <input name="variations[${variationIndex}][colors]" type="text" class="browser-default inp" placeholder="e.g. Red,Blue,Green" required>
                </div>
                <div class="col s12 m3">
                    <label>Price for this Variation:</label>
                    <input name="variations[${variationIndex}][price]" type="number" class="browser-default inp" placeholder="Variation Price" required>
                </div>
                <div class="col s12 right-align">
                                        <button type="button" class="btn red" onclick="removeVariation(this)">Remove</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', newVariation);
        variationIndex++;
    });

    function removeVariation(button) {
        let row = button.closest('.variation-row');
        row.remove();
    }
    </script>
@endsection
