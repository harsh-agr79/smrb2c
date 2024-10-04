@extends('layouts/admin')

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
    <div class="mp-card" style="margin-top: 10px;">
        <h5 class="center">Privacy Policy</h5>
        <form action="{{ route('editpolicy') }}" method="post" class="row">
            @csrf
            <div class="col s12" style="margin-top: 20px;">
                <div id="editor">
                    {!! $data->policy !!}
                </div>
                <input type="hidden" name="policy" id="details">
            </div>
            <div class="col center s12">
                <button type="submit" class="btn green">Save</button>
            </div>
        </form>
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
    </script>
@endsection
