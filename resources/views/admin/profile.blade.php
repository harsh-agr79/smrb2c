@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="row center" style="font-size: 20px;">
                <div class="col s12">Name: {{$admin->name}}</div>
                <div class="col s12">Email: {{$admin->email}}</div>
                <div class="col s12">User Id: {{$admin->userid}}</div>
                <div class="col s12"><button class="btn green darken-2  modal-trigger"  href="#modal1">Change Password</button></div>
            </div>
        </div>
    </div>

    <div id="modal1" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s2"></div>
                <form class="row col s8" id="change_password">
                    <div class="col s12">
                        <label>Current Password:</label>
                        <input type="password" id="old" name="old" class="browser-default inp" placeholder="Current Password">
                    </div>
                    <div class="col s12">
                        <label>New Password:</label>
                        <input type="password" id="new" name="new" class="browser-default inp" placeholder="Current Password">
                    </div>
                    <div class="col s12">
                        <label>New Password Again:</label>
                        <input type="password" id="new2" name="newagain" class="browser-default inp" placeholder="Current Password">
                    </div>
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-2">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
           
        </div>
      </div>

      <script>
        $('#change_password').on('submit', (e)=>{
            e.preventDefault()
            let formData = new FormData($("#change_password")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/admin/changepassword",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: response});
                    if(response == "Password Changed"){
                        $('#modal1').modal("close")
                        $('#old').val("")
                        $('#new').val("")
                        $('#new2').val("")
                    }
                }
            })
        })
      </script>
@endsection