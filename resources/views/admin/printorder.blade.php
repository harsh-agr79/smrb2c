<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <style>
        td {
            padding: 2px;
            font-size: 10px;
            font-weight: 600;
        }

        .cont {
            margin-left: 30vw;
            margin-right: 30vw;
        }

        @media screen and (max-width: 1100px) {
            .cont {
                margin: 0;
            }
        }
    </style>
    <div class="center">
        <button class="btn green accent-4" onclick="print()">
            Print
        </button>
    </div>
    @php
        $cus = DB::table('customers')
            ->where('id', $data[0]->user_id)
            ->first();
        $disc = 0;
        $disc2 = 0;
        foreach ($data as $item) {
            if ($item->discount > 0 || $item->sdis > 0) {
                $disc = $item->discount;
                $disc2 = $item->sdis;
                break;
            }
        }
    @endphp
    <div id="invoice" style="padding: 10px;">
        <div class="row" style="font-size: 10px;">
            <div class="col s4">
                {{-- <span>My Power</span><br>
                <span>+977 9849239275</span><br>
                <span>Kathmandu</span><br> --}}
            </div>
            <div class="col s4 center">
                {{-- <img src="{{asset('assets/light.png')}}" height="60" alt=""> --}}
            </div>
            <div class="col s4 right-align">
                Date: {{ date('Y-m-d', strtotime($data[0]->date)) }} <br>
                Miti: {{ getNepaliDate($data[0]->date) }}
            </div>
        </div>
        <div>
            <div>
                <span style="padding: 5px 10px; font-size: 15px; font-weight: 600;"
                    class="green accent-4 black-text">Bill To</span><br>
                <span>Name: {{ $data[0]->name }}</span><br>
                <span>Shop Name: {{ $cus->shopname }}</span><br>
                <span>Address: {{ $cus->address }}</span><br>
                <span>Contact: {{ $cus->contact }}</span><br>
            </div>
        </div>
        <table>
            <thead class="green lighten-3">
                <th>SN</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Discounted</th>
            </thead>
            <tbody>
                @php
                    $a = 0;
                    $total = 0;
                    $total2 = 0;
                @endphp
                @foreach ($data as $item)
                    <tr class="@if($item->net != NULL) grey lighten-2 @endif">
                        <td>{{ $a = $a + 1 }}</td>
                        <td style="padding: 0; margin: 0;">
                            {{ $item->item }} @if($item->net != NULL) <span>(NET)</span> @endif
                            <br>
                            <span style="font-size: 5px; margin-top: -20px !important; padding-top: -20px !important;">{{ $item->brand }}
                                {{ $item->category }}</span>
                        </td>
                        <td>{{ $item->approvedquantity }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $b = $item->price * $item->approvedquantity }}</td>
                        <td>{{ money($c = $item->approvedquantity * $item->price * (1 - 0.01 * $item->discount) * (1 - 0.01 * $item->sdis)) }}
                        </td>
                        <span class="hide">{{ $total = $total + $b }}</span>
                        <span class="hide">{{ $total2 = $total2 + $c }}</span>
                    </tr>
                @endforeach
                <tr class="green lighten-3">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td>Rs. {{ money($total) }}</td>
                    <td>Rs. {{ money($total2) }}</td>
                </tr>
                @if ($disc > 0)
                    <tr class="green lighten-3">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Discount</td>
                        <td>{{ $disc }}%</td>
                        <td></td>
                    </tr>
                @endif
                @if ($disc2 > 0)
                    <tr class="green lighten-3">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Discount</td>
                        <td>{{ $disc2 }}%</td>
                        <td></td>
                    </tr>
                @endif
                <tr class="green lighten-3">
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td>Discounted</td>
                    <td>Rs. {{ money($total2) }}</td>
                    <td>Rs. {{ money($total2) }}</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 100px">

        </div>
    </div>



    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        function print() {
            var inoice = $('#invoice');
            html2pdf(invoice, {
                filename: `{{ $data[0]->order_id }}` + '.pdf'
            });
        }
    </script>
</body>

</html>
