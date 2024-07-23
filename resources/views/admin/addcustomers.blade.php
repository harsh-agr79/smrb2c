@extends('layouts.admin')

@section('main')
    <div>
        <h5 class="center">Add Customer</h5>
        @error('userid')
            <div class="red-text">{{ $message }}</div>
        @enderror
        @error('email')
            <div class="red-text">{{ $message }}</div>
        @enderror
        @error('contact')
            <div class="red-text">{{ $message }}</div>
        @enderror
        @error('tax_number')
            <div class="red-text">{{ $message }}</div>
        @enderror
        <div class="mp-card">
            <form action="{{ route('addcust') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col m6 s12">
                        <label>Name :</label><input name="name" type="text" class="browser-default inp"
                            placeholder="Name" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Shop Name :</label><input name="shopname" type="text" class="browser-default inp"
                            placeholder="Shop Name" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Email :</label><input name="email" type="email" class="browser-default inp"
                            placeholder="Email" required>
                    </div>
                    <div class="col m6 s12">
                        <label>User ID :</label><input name="userid" type="text" class="browser-default inp"
                            placeholder="User Id" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Password :</label><input name="password" type="password" class="browser-default inp"
                            placeholder="Password" required>
                    </div>
                    <div class="col m6 s12">
                        <label>DOB :</label><input name="dob" type="date" class="browser-default inp"
                            placeholder="dob">
                    </div>
                    <div class="col m6 s12">
                        <label>Contact :</label><input name="contact" type="number" class="browser-default inp"
                            placeholder="Contact" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Contact 2 :</label><input name="contact2" type="number" class="browser-default inp"
                            placeholder="contact2">
                    </div>
                    <div class="col m6 s12">
                        <label>Address :</label><input name="address" type="text" class="browser-default inp"
                            placeholder="Address" required>
                    </div>
                    <div class="col m6 s12">
                        <label>Area :</label><input name="area" type="text" class="browser-default inp"
                            placeholder="Area" required>
                    </div>
                    <div class="col m6 s12">
                        <label>State :</label>
                        <select id="state" name="state" class="browser-default inp">

                            <option class="black-text" value="" selected disabled>Select State</option>

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

                            <option class="black-text" value="" selected disabled>Select District</option>

                        </select>
                    </div>
                    <div class="col m6 s12">
                        <select id="MySelct" name="marketer" searchname="myselectsearch"
                            searchable="Select Marketer">
                            <option value="" selected>Select Marketer</option>

                            @foreach ($marketers as $item)
                                <option value="{{ $item->id }}">{{ $item->userid }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>Tax Type :</label>
                        <select name="tax_type" class="browser-default inp">
                            <option value="" selected disabled>Tax Type</option>
                            <option value="VAT">VAT</option>
                            <option value="PAN">PAN</option>
                        </select>
                    </div>
                    <div class="col m6 s12">
                        <label>Tax Number :</label><input name="tax_number" type="text" class="browser-default inp"
                            placeholder="Tax Number">
                    </div>
                    <div class="col m6 s12">
                        <label>Type :</label>
                        <select name="type" class="browser-default inp" required>
                            <option value="" selected disabled>Type</option>
                            <option value="wholesaler">Wholesaler</option>
                            <option value="retailer">Retailer</option>
                        </select>
                    </div>
                    <div class="col s12 center">
                        <h4>Brands Allowed</h4>
                    </div>
                    @foreach ($brands as $item)
                        <div class="col m6 s12">
                            <label>
                                <input type="checkbox" name="brands[]" value="{{ $item->id }}" />
                                <span>{{ $item->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
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
                "parasi"
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
