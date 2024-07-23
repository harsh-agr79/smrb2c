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

        {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"
        integrity="sha512-sn/GHTj+FCxK5wam7k9w4gPPm6zss4Zwl/X9wgrvGMFbnedR8lTUSLdsolDRBRzsX6N+YgG6OWyvn9qaFVXH9w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        td {
            padding: 0 !important;
            font-size: 11px;
            font-weight: 800;
        }
        h5{
            font-weight: 700;
        }
/* 
        #invoice{
            transform: scale(2);
        } */
    </style>

    <div id="invoice" style="padding: 20px;">
        <div class="row valign-wrapper">
            <div class="col s4 center"><h5>SAMAR SUPPLIERS</h5></div>
            <div class="col s4 center"><img src="{{ asset('/logo/logo.jpg') }}" style="height: 150px" alt=""></div>
            <div class="col s4 center valign-wrapper"><img src="{{asset('/images/whatsapp.webp')}}" style="height: 70px;" alt=""><h5>9849287007</h5></div>
        </div>

        <table>
            <thead>
                <th>Name</th>
                <th>category</th>
                <th>Brand</th>
                <th>Image</th>
                <th>Price</th>
                <!-- <th>New</th>
                <th>Offer</th> -->
                <th>Details</th>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td><span>{{ $item->name }} @if ($item->net != null)
                                    <span class="red-text">(NET)</span>
                                @endif
                            </span><br>
                            @if ($item->featured != null)
                                <span class="red" style="padding:0 5px; margin: 3px; border-radius: 4px;">NEW!</span>
                            @endif <br>
                            @if ($item->offer != null)
                                <span class="amber"
                                    style="padding: 0 5px; border-radius: 4px; margin: 3px;">{{ $item->offer }}</span>
                            @endif <br>
                            @if ($item->stock != null)
                                <span class="red-text">Out of Stock</span>
                            @endif
                        </td>

                        <td>{{ $item->category }}</td>
                        <td>{{ $item->brand }}</td>
                        @php
                            $a = explode('|', $item->images);
                        @endphp
                        <td><img src="@if ($item->images != '' || $item->images != null) {{ asset(explode('|', $item->images)[count($a) - 1]) }}@else{{ asset('images/prod.jpg') }} @endif"
                                style="height: 150px;" alt=""></td>

                        <td>{{ $item->price }}</td>
                        <td style="white-space: pre-wrap"><br>{{ $item->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>



    <script>
        $(document).ready(function() {
            // screenshot();

            // function insertBreaks() {

            //     //get table rows in html 
            //     var rows = document.querySelectorAll('table > tbody > tr');
            //     let current_page_height = 0;
            //     let max_page_height = 750; //adjust max sizeof page in px 
            //     rows.forEach(row => {
            //         var row_height = row.offsetHeight;
            //         current_page_height = current_page_height + row_height
            //         //If the sum of page rows heights are bigger thant my limit, then insert break
            //         if (current_page_height > max_page_height) {
            //             current_page_height = 0;
            //             $(` <tr style="page-break-after:always;">
            //         </tr>`).insertAfter(row);
            //             console.log("break");
            //         }
            //     });
            // }
            // insertBreaks();

            function print() {
                var invoice = document.getElementById('invoice');
                var opt = {
                    filename: 'catalog.pdf',
                    // margin: [10, 0, 10, 0],
                    image: {
                        type: 'jpeg',
                        quality: 0.9
                    },
                    autoPaging: 'text',
                    html2canvas: {
                        scale: 1.9,
                        logging: true,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'p'
                    },
                    pagebreak: {
                        avoid: ['tr','td'],
                        mode: ['css', 'legacy']
                    }
                };
                console.log("works")
                html2pdf().set(opt).from(invoice).save();
                // html2pdf(invoice, opt);
                // setTimeout(function() { window.close() }, 10000);

            }

            print();

        })
    </script>
</body>

</html>
