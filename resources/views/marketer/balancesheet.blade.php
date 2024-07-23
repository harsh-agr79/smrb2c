@extends('layouts/marketer')

@section('main')
    <style>
        th,
        td {
            border: 1px solid;
        }
    </style>
    <div class="hide">
        {{ $obc = 0 }}
        {{ $obd = 0 }}

        @if ($oldorders->isEmpty())
            {{ $oo = 0 }}
        @else
            {{ $oo = $oldorders['0']->sum}}
        @endif
        @if ($oldpayments->isEmpty())
            {{ $op = 0 }}
        @else
            {{ $op = $oldpayments['0']->sum }}
        @endif
        @if ($oldslr->isEmpty())
            {{ $os = 0 }}
        @else
            {{ $os = $oldslr['0']->sum }}
        @endif
        @if ($oldexp->isEmpty())
            {{ $oe = 0 }}
        @else
            {{ $oe = $oldexp['0']->sum }}
        @endif

        {{ $tobd = $oo + $obd + $oe }}
        {{ $tobc = $op + $os + $obc }}

        @if ($tobd > $tobc)
            {{ $tod = $tobd - $tobc }}
            {{ $toc = 0 }}
            {{ $runb = $tod }}
        @elseif($tobd < $tobc)
            {{ $tod = 0 }}
            {{ $toc = $tobc - $tobd }}
            {{ $runb = $tobd - $tobc }}
        @else
            {{ $tod = 0 }}
            {{ $toc = 0 }}
            {{ $runb = 0 }}
        @endif
        @php
            $credit = $toc;
            $debit = $tod;
        @endphp
    </div>
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="center">
                <h6>Statement of : {{ $cus->name }}</h6>
            </div>
            <div class="green center" style="padding: 5px; margin-top: 20px; border-radius: 10px;">
                <h6 class="black-text" style="font-weight: 600;">
                    @php
                        $bal = explode('|', $cus->balance);
                        $sl_r = 0;
                    @endphp
                    @if ($bal[0] == 'red')
                        Amount to recieve: {{ $bal[1] }}
                    @else
                        Amount to pay: {{ $bal[1] }}
                    @endif
                </h6>
            </div>
            <div class="row center" style="margin-top: 10px;">
                <div class="col s4">
                    <label><input type="checkbox" id="photo" onchange="tog()" /><span class="textcol">Show
                            Naration</span></label>
                </div>
                <div class="col s4">
                    <label><input type="checkbox" id="photo" onchange="vou()" /><span class="textcol">Show
                           Voucher</span></label>
                </div>
                <div class="col s4">
                    <label><input type="checkbox" id="photo" onchange="ned()" /><span class="textcol">English Date</span></label>
                </div>
                <form>
                    <div class="col s6">
                        <label>From:</label>
                        <input type="date" name="date" value="{{ $date }}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="col s6">
                        <label>To:</label>
                        <input type="date" name="date2" value="{{ $date2 }}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="col s4" style="margin-top: 10px;">
                        <button class="btn green black-text">
                            Apply<i class="material-icons right">send</i>
                        </button>
                    </div>
                </form>
                <div class="col s4" style="margin-top: 10px;">
                    <a class="btn green black-text" href="{{ url('/balancesheet/' . $cus->name) }}">
                        Clear
                    </a>
                </div>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 10px; overflow-x: scroll;">
            <table class="sortable">
                <thead>
                    <tr>
                        <th class="date">Date</th>
                        <th>Type</th>
                        <th>id</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Running balance</th>
                        <th class="narcol" style="display: none;">Narartion</th>
                        <th class="voucol" style="display: none;">Voucher</th>
                        {{-- <th>Running balance</th> --}}
                    </tr>
                   
                </thead>
                <tbody>
                    <tr style="font-weight: 700">
                        <td sorttable_customkey= "-10000">From Before: {{$date}}</td>
                        <td></td>
                        <td>Opening Balance</td>
                        <td>{{ $tod }}</td>
                        <td>{{ $toc }}</td>
                        <td></td>
                    </tr>
                    @if ($data == null)
                    @else
                        @for ($i = 0; $i < count($data); $i++)
                            <tr>
                                <td sorttable_customkey="{{ $i }}"><span
                                        class="nepalidate">{{ getNepaliDate($data[$i]['created']) }}</span><span
                                        class="englishdate"
                                        style="display:none;">{{ date('d-m-y', strtotime($data[$i]['created'])) }}</span>
                                </td>
                                <td>{{ $data[$i]['type'] }}</td>
                                <td>
                                    @if ($data[$i]['type'] == 'sale')
                                            <a href="{{ url('/marketer/detail/' . $data[$i]['ent_id']) }}">
                                    @elseif($data[$i]['type'] == 'Payment' || $data[$i]['type'] == 'Salesreturn')
                                    @php
                                        if($data[$i]['type'] == 'Salesreturn'){
                                            $sl_r = $sl_r + $data[$i]['credit'];
                                        }
                                    @endphp
                                        <a href="{{ url('/marketer/editpayment/' . $data[$i]['ent_id']) }}">
                                    @elseif($data[$i]['type'] == 'Sales Return')
                                            {{$data[$i]['ent_id']}}
                                    @elseif($data[$i]['type'] == 'expense')
                                                {{ $data[$i]['ent_id'] }}
                                    @endif
                                    {{ $data[$i]['ent_id'] }}</a>
                                </td>
                                <td>
                                    @if ($data[$i]['debit'] != 0)
                                        {{ money($data[$i]['debit']) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data[$i]['credit'] != 0)
                                        {{ money($data[$i]['credit']) }}
                                    @endif
                                </td>
                                @if ($runb + $data[$i]['debit'] - $data[$i]['credit'] > 0)
                                    <td class="red lighten-5 black-text">
                                        {{ money(abs($runb = $runb + $data[$i]['debit'] - $data[$i]['credit'])) }}</td>
                                @else
                                    <td class="green lighten-5 black-text">
                                        {{ money(abs($runb = $runb + $data[$i]['debit'] - $data[$i]['credit'])) }}</td>
                                @endif
                                <td class="narcol" style="display:none;">{{ $data[$i]['nar'] }}</td>
                                <td class="voucol" style="display:none;">{{ $data[$i]['vou'] }}</td>
                            </tr>
                        @endfor
                    @endif
                <tfoot style="font-weight: 700;">
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total Sales</td>
                        <td>
                            @if (!$cuorsum->isEmpty())
                                {{ $cuorsum[0]->sum }}
                                @php
                                    $debit = $debit + $cuorsum[0]->sum;
                                @endphp
                            @else
                                0
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total Expense</td>
                        <td>
                            @if (!$cuexsum->isEmpty())
                                {{ $cuexsum[0]->sum }}
                                @php
                                    $debit = $debit + $cuexsum[0]->sum;
                                @endphp
                            @else
                                0
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total Payment</td>
                        <td></td>
                        <td>
                            @if (!$cupysum->isEmpty())
                                {{ $cupysum[0]->sum - $sl_r}}
                                @php
                                    $credit = $credit + $cupysum[0]->sum;
                                @endphp
                            @else
                                0
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total Salesreturn</td>
                        <td></td>
                        <td>
                            @if (!$cuslrsum->isEmpty())
                                {{ $cuslrsum[0]->sum + $sl_r }}
                                @php
                                    $credit = $credit + $cuslrsum[0]->sum;
                                @endphp
                            @else
                               {{$sl_r }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td>{{ $debit }}</td>
                        <td>{{ $credit }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Balance</td>
                        @if ($bal[0] == 'red')
                            <td> {{ $bal[1] }}</td>
                            <td></td>
                        @else
                            <td></td>
                            <td> {{ $bal[1] }}</td>
                        @endif
                        <td></td>
                    </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>





    <script>
         function tog() {
            var narcol = document.getElementsByClassName('narcol');
            $(narcol).toggle()
        }

        function vou() {
            var voucol = document.getElementsByClassName('voucol');
            $(voucol).toggle()
        }

        function ned() {
            var engd = document.getElementsByClassName('englishdate');
            var nepd = document.getElementsByClassName('nepalidate');
            $(engd).toggle()
            $(nepd).toggle()
        }
    </script>
@endsection
