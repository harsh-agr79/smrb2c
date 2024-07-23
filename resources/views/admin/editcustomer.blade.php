@extends('layouts.admin')

@section('main')
    <div>
        <h5 class="center">Edit {{ $cus->name }}</h5>




        <div class="mp-card">
            <form action="{{ route('editcust') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row page-form-row">
                    <div class="col s12 center">
                        @if ($cus->profileimg == null)
                            <img src="{{ asset('images/user.png') }}" style="height: 100px; border-radius: 50%;"
                                alt="">
                        @else
                            <img src="{{ asset($cus->profileimg) }}" style="height: 100px; border-radius: 50%;"
                                alt="">
                        @endif
                    </div>
                    <div class="col s12 container">
                        {{-- <div class="container"> --}}
                        <div class="file-field input-field row">
                            <div class="col s4"></div>
                            <div class="btn-large green darken-3 col m4 s12">
                                <span>Change DP</span>
                                <input type="file" name="dp">
                            </div>
                            <div class="col s4"></div>
                        </div>
                        {{-- </div> --}}

                    </div>
                    <input type="hidden" name="olddp" value="{{ $cus->profileimg }}">
                    <div class="col m6 s12">
                        <label>Name :</label><input name="name" value="{{ $cus->name }}" type="text"
                            class="browser-default inp" placeholder="Name" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Shop Name :</label><input name="shopname" type="text" class="browser-default inp"
                            value="{{ $cus->shopname }}" placeholder="Shop Name" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Email :</label><input name="email" value="{{ $cus->email }}" type="email"
                            class="browser-default inp" placeholder="Email" required>
                        @error('email')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col m6 s12">
                        <label>User ID :</label><input name="userid" value="{{ $cus->userid }}" type="text"
                            class="browser-default inp" placeholder="User Id" required>
                        @error('userid')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col m6 s12">
                        <label>Password :</label><input name="password" type="password" class="browser-default inp"
                            placeholder="Password">
                    </div>
                    <div class="col m6 s12">
                        <label>DOB :</label><input name="dob" type="date" value="{{ $cus->dob }}"
                            class="browser-default inp" placeholder="dob" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Contact :</label><input name="contact" value="{{ $cus->contact }}" type="number"
                            class="browser-default inp" placeholder="Contact" required>
                        @error('contact')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col m6 s12">
                        <label>Contact 2 :</label><input name="contact2" value="{{ $cus->contact2 }}" type="number"
                            class="browser-default inp" placeholder="contact2">
                    </div>
                    <div class="col m6 s12">
                        <label>Address :</label><input name="address" value="{{ $cus->address }}" type="text"
                            class="browser-default inp" placeholder="Address" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Area :</label><input name="area" type="text" value="{{ $cus->area }}"
                            class="browser-default inp" placeholder="Area">
                    </div>
                    <div class="col m6 s12">
                        <label>State :</label>
                        <select id="state" name="state" class="browser-default inp">
                            @if ($cus->state != null)
                                <option value="{{ $cus->state }}" selected>{{ $cus->state }}</option>
                            @else
                                <option class="black-text" value="" selected disabled>Select State</option>
                            @endif


                            <option value="Bagmati">Bagmati</option>
                            <option value="Karnali">Karnali</option>
                            <option value="Koshi">Koshi</option>
                            <option value="Gandaki">Gandaki</option>
                            <option value="Madhesh">Madhesh</option>
                            <option value="Lumbini">Lumbini</option>
                            <option value="Sudur Paschim">Sudur Paschim</option>
                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>District :</label>
                        <select id="district" name="district" class="browser-default inp">
                            @if ($cus->district != null)
                                <option value="{{ $cus->district }}" selected>{{ $cus->district }}</option>
                            @else
                                <option class="black-text" value="" selected disabled>Select District</option>
                            @endif

                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>Marketer :</label>
                        <select id="MySelct" name="marketer" searchname="myselectsearch"
                            searchable="Select Marketer">
                            
                            @if ($cus->marketer != NULL)
                            <option value="" selected>Select Marketer</option>
                            <option value="{{$cus->marketer_id}}" selected>{{$cus->marketer}}</option>
                            @else
                            <option value="" selected>Select Marketer</option>
                            @endif
                            @foreach ($marketers as $item)
                                <option value="{{ $item->id }}">{{ $item->userid }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>Tax Type :</label>
                        <select name="tax_type" class="browser-default inp">
                            @if ($cus->tax_type != null)
                                <option value="{{ $cus->tax_type }}" selected>{{ $cus->tax_type }}</option>
                            @else
                                <option class="black-text" value="" selected disabled>Select State</option>
                            @endif
                            <option value="VAT">VAT</option>
                            <option value="PAN">PAN</option>
                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>Tax Number :</label><input name="tax_number" value="{{ $cus->tax_number }}"
                            type="text" class="browser-default inp" placeholder="Name">
                        @error('tax_num')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col m6 s12">
                        <label>Type :</label>
                        <select name="type" class="browser-default inp" required>
                            @if ($cus->type != null)
                                <option value="{{ $cus->type }}" selected>{{ $cus->type }}</option>
                            @else
                                <option class="black-text" value="" selected disabled>Select type</option>
                            @endif
                            <option value="wholesaler">Wholesaler</option>
                            <option value="retailer">Retailer</option>
                        </select>
                    </div>
                    <div class="col s12 center">
                        <h4>Brands Allowed</h4>
                    </div>
                    @php
                        $brd = explode("|", $cus->brands)
                    @endphp
                        @foreach ($brands as $item)
                        <div class="col m6 s12">
                            <label>
                                <input type="checkbox" name="brands[]" value="{{$item->id}}" @if (in_array($item->id, $brd))
                                    checked
                                @endif/>
                                <span>{{ $item->name }}</span>
                            </label>
                    </div>
                        @endforeach
                </div>
                <input type="hidden" value="{{ $cus->id }}" name="id">
                <input type="hidden" value="{{ $cus->uniqueid }}" name="uniqueid">
                <div class="fixed-action-btn">
                    <button class="btn-large red">
                        Submit <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#state').on('change', function() {
            var value = $('#state').val()
            // console.log(value);
            var bag = [
                "sindhuli",
                "ramechhap",
                "dolakha",
                "bhaktapur",
                "dhading",
                "kathmandu",
                "kavrepalanchok",
                "lalitpur",
                "nuwakot",
                "rasuwa",
                "sindhupalchok",
                "chitwan",
                "makwanpur"
            ]
            var gan = [
                "baglung",
                "gorkha",
                "kaski",
                "lamjung",
                "manang",
                "mustang",
                "myagdi",
                "nawalpur",
                "parbat",
                "syangja",
                "tanahun"
            ]
            var kar = [
                "western rukum ",
                "salyan",
                "dolpa",
                "humla",
                "jumla",
                "kalikot",
                "mugu",
                "surkhet",
                "dailekh",
                "jajarkot"
            ]
            var lum = [
                "kapilvastu",
                "rupandehi",
                "arghakhanchi",
                "gulmi",
                "palpa",
                "dang",
                "pyuthan",
                "rolpa",
                "eastern rukum ",
                "banke",
                "bardiya",
                "Parasi"
            ]
            var mad = [
                "sarlahi",
                "dhanusha",
                "bara",
                "rautahat",
                "saptari",
                "siraha",
                "mahottari",
                "parsa"
            ]
            var kos = [
                "bhojpur",
                "dhankuta",
                "ilam",
                "jhapa",
                "khotang",
                "morang",
                "okhaldhunga",
                "panchthar",
                "sankhuwasabha",
                "solukhumbu",
                "sunsari",
                "taplejung",
                "terhathum",
                "udayapur"
            ]
            var sud = [
                "achham",
                "baitadi",
                "bajhang",
                "bajura",
                "dadeldhura",
                "darchula",
                "doti",
                "kailali",
                "kanchanpur"
            ]

            if (value == 'Bagmati') {
                var dis = bag;
            }
            if (value == 'Gandaki') {
                var dis = gan;
            }
            if (value == 'Karnali') {
                var dis = kar
            }
            if (value == 'Madhesh') {
                var dis = mad
            }
            if (value == 'Koshi') {
                var dis = kos
            }
            if (value == 'Lumbini') {
                var dis = lum
            }
            if (value == 'Sudur Paschim') {
                var dis = sud
            }
            console.log(dis)
            var sc = $('#district');
            sc.empty();
            sc.append($('<option></option>').attr('value', null).attr('selected', 'true').text(
                'Select District'));
            // dis.forEach(element => {
            //     $sc.append($('<option></option>')
            //                 .attr("value", value.subcategory).text(value.subcategory))
            // });
            for (let i = 0; i < dis.length; i++) {
                sc.append($('<option></option>')
                    .attr("value", dis[i]).text(dis[i]))
            }
        })
    </script>
@endsection
