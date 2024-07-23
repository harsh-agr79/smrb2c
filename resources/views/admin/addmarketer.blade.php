@extends('layouts/admin')

@section('main')
    <form action="{{ route('addmarketerprocess') }}" method="post">
        @csrf
        <input type="hidden" value="{{ $id }}" name="id">
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Add Marketer</h6>
            </div>

            <div class="row">
                <div class="col s12 row">
                    <div class="col s6">
                        Name:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{ $name }}" name="name"
                            class="inp black-text browser-default" placeholder="Name">
                        <input type="hidden" name="name2" value="{{ $name }}">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        User ID:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{ $userid }}" name="userid"
                            class="inp black-text browser-default" placeholder="User Id">
                        <input type="hidden" name="userid2" value="{{ $userid }}">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Contact:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{ $contact }}" name="contact"
                            class="inp black-text browser-default" placeholder="contact">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Password:
                    </div>
                    <div class='input-field col s6'>
                        <input class='validate browser-default inp black-text' placeholder="password" type='password'
                            name='passwordnew' id='password' @if ($id == '') required @endif />
                        <span toggle="#password" class="field-icon toggle-password"><span
                                class="material-icons black-text">visibility</span></span>
                    </div>
                    <input type="hidden" name="passwordold" value="{{ $password }}">
                </div>
            </div>
        </div>


        <div class="fixed-action-btn">
            <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                style="border-radius: 10px;">
                Submit
                <i class="left material-icons">send</i>
            </button>
        </div>
    </form>
    <script>
        var clicked = 0;

        $(".toggle-password").click(function(e) {
            e.preventDefault();

            $(this).toggleClass("toggle-password");
            if (clicked == 0) {
                $(this).html('<span class="material-icons">visibility_off</span >');
                clicked = 1;
            } else {
                $(this).html('<span class="material-icons">visibility</span >');
                clicked = 0;
            }

            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endsection
