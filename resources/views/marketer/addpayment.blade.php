@extends('layouts/marketer')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Payment</h6>
            </div>
            <form action="{{route('marketer_addpay')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col s12 row">
                        <div class="col s6">
                            date:
                        </div>
                        <div class="col s6">
                            <input type="datetime-local" step="any" value="{{ $date }}" name="date"
                                class="inp black-text browser-default" placeholder="Date">
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Name:
                        </div>
                        <div class="input-field col s6" style="margin-top: 0;">
                            <input type="text" name="name" value="{{$name}}" accesskey="c" id="customer" placeholder="Customer"
                                class="autocomplete browser-default inp black-text" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Payment / Salesreturn
                        </div>
                        <div class="col s6">
                            <select name="type" class="browser-default inp">
                                @if ($type != '')
                                    <option value="{{$type}}" selected>{{$type}}</option>
                                    <option value="Payment">Payment</option>
                                    <option value="Salesreturn">Sales Return</option>
                                @else
                                <option value="Payment" Selected>Payment</option>
                                <option value="Salesreturn">Sales Return</option>
                                @endif
                               
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Amount:
                        </div>
                        <div class="col s6">
                            <input type="number" name="amount" value="{{$amount}}" class="inp black-text browser-default"
                                placeholder="Amount" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Voucher:
                        </div>
                        <div class="col s6">
                            <input type="text" name="voucher" value="{{$voucher}}" class="inp black-text browser-default"
                                placeholder="Voucher">
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Remarks:
                        </div>
                        <div class="col s6">
                            <input type="text" name="remarks" value="{{$remarks}}" class="inp black-text browser-default"
                                placeholder="Remarks">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="payid" value="{{$payid}}">
                <div class="fixed-action-btn">
                    <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                        style="border-radius: 10px;">
                        Submit
                        <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/marketer/findcustomer',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
    </script>
@endsection
