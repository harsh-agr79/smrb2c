@extends('layouts/customer')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px; margin-bottom: 20px;">
            <form action="{{ route('editprofile') }}" enctype="multipart/form-data" method="POST" id="profile">
                @csrf
                <div class="row page-form-row container">
                    <div class="col s12 center">
                        @if ($user->profileimg == null)
                            <img src="{{ asset('images/user.png') }}" style="height: 100px; border-radius: 50%;"
                                alt="">
                        @else
                            <img src="{{ asset($user->profileimg) }}" style="height: 100px; border-radius: 50%;"
                                alt="">
                        @endif
                    </div>
                    <div class="col s12 container">
                        {{-- <div class="container"> --}}
                            <div class="file-field input-field row">
                                <div class="col s4"></div>
                                <div class="btn-large green darken-3 col m4 s12">
                                    <span>Change DP</span>
                                    <input type="file" name="dp" onchange="$('#profile').submit();">
                                </div>
                                <div class="col s4"></div>
                            </div>
                        {{-- </div> --}}

                    </div>
                    <input type="hidden" name="olddp" value="{{ $user->profileimg }}">
                    <div class="col  s12">
                        <label>DOB :</label><input name="dob" type="date" value="{{ $user->dob }}"
                            class="browser-default inp" placeholder="dob" required>
                    </div>
                    <div class="col  s12">
                        <label>Contact :</label><input name="contact" value="{{ $user->contact }}" type="number"
                            class="browser-default inp" placeholder="Contact" required>
                        @error('contact')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col  s12">
                        <label>Contact 2 :</label><input name="contact2" value="{{ $user->contact2 }}" type="number"
                            class="browser-default inp" placeholder="contact2">
                    </div>
                    <div class="col  s12">
                        <label>Address :</label><input name="address" value="{{ $user->address }}" type="text"
                            class="browser-default inp" placeholder="Address" required>
                    </div>
                    <div class="col  s12">
                        <label>Tax Type :</label>
                        <select name="tax_type" class="browser-default inp">
                            @if ($user->tax_type != null)
                                <option value="{{ $user->tax_type }}" selected>{{ $user->tax_type }}</option>
                            @else
                                <option class="black-text" value="" selected disabled>Select State</option>
                            @endif
                            <option value="VAT">VAT</option>
                            <option value="PAN">PAN</option>
                        </select>
                    </div>
                    <div class="col  s12">
                        <label>Tax Number :</label><input name="tax_number" value="{{ $user->tax_number }}"
                            type="text" class="browser-default inp" placeholder="Name">
                        @error('tax_num')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="hidden" value="{{ $user->id }}" name="id">
                <input type="hidden" value="{{ $user->uniqueid }}" name="uniqueid">
                <div class="fixed-action-btn">
                    <button class="btn-large red">
                        Submit <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection