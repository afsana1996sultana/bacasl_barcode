<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daratech BD</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <style>
        p {
            margin: -2px;
        }
        p.barcode_img {
            text-align: center;
            padding-top: 4px;
        }
        text {
            display: none;
        }
        .barcode-wrapper {
            width: 1.5in;
            height: 1in;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
        <div style="position: absolute; left: 50%; transform: translateX(-50%);">
            @if($productstocks != null)
                @php
                    $d_value = json_decode($decode);
                @endphp
                @foreach ($productstocks as $productstock)
                    @php
                        $discount = 0;
                        $amount = $productstock->price;
                        $allproduct = App\Models\Product::findOrFail($productstock->product_id);
                        if ($allproduct->discount_price > 0) {
                            if ($allproduct->discount_type == 1) {
                                $discount = $allproduct->discount_price;
                            } elseif ($allproduct->discount_type == 2) {
                                $discount = ($productstock->price * $allproduct->discount_price) / 100;
                            }
                            $amount = $productstock->price - $discount;
                        }
                    @endphp
                    @foreach ($d_value as $key => $item)
                        @if($key == $productstock->varient)
                            @for ($i = 1; $i <= $item->qty; $i++)
                                <div class="barcode-wrapper">
                                    <p style="text-align: center; font-weight: bold; font-size: 12px;">{{ get_setting('site_name')->value }}</p>
                                    <p style="text-align: center; font-weight: bold; font-size: 12px;">Price: {{ $amount }}</p>
                                    <p style="text-align: center; font-weight: bold; font-size: 12px;" class="show">Size: {{$productstock->varient}}</p>
                                    <p style="text-align: center; font-weight: bold; font-size: 12px;" class="show">Code: {{$productstock->stock_code}}</p>
                                    <p class="barcode_img"><svg id="barcode{{ $productstock->id . $i }}"></svg></p>
                                </div>
                            @endfor
                        @endif
                    @endforeach
                @endforeach
            @endif

            @if($product != null)
                @php
                    $discount = 0;
                    $amount = $product->regular_price;
                    if ($product->discount_price > 0) {
                        if ($product->discount_type == 1) {
                            $discount = $product->discount_price;
                            $amount = $product->regular_price - $discount;
                        } elseif ($product->discount_type == 2) {
                            $discount = ($product->regular_price * $product->discount_price) / 100;
                            $amount = $product->regular_price - $discount;
                        } else {
                            $amount = $product->regular_price;
                        }
                    }
                @endphp
                @for ($i = 1; $i <= $qty; $i++)
                    <div class="barcode-wrapper">
                        <p style="text-align: center; font-size: 12px; font-weight: bold">{{ get_setting('site_name')->value }}</p>
                        <p style="text-align: center; font-size: 12px; font-weight: bold">Price: {{ $amount }}</p>
                        <p style="text-align: center; font-size: 12px; font-weight: bold">Code: {{$product->product_code }}</p>
                        <p class="barcode_img"><svg id="barcode{{ $product->id . $i }}"></svg></p>
                    </div>
                @endfor
            @endif
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <script>
        window.onload = function() {
            window.print();
        };

        document.addEventListener("DOMContentLoaded", function() {
            @if($productstocks != null)
                @foreach ($productstocks as $productstock)
                    @foreach ($d_value as $key => $item)
                        @if($key == $productstock->varient)
                            @for ($i = 1; $i <= $item->qty; $i++)
                                JsBarcode("#barcode{{ $productstock->id . $i }}", "{{ $productstock->stock_code }}", {
                                    format: "CODE128",
                                    width: 1.5,
                                    height: 40,
                                    displayValue: true,
                                    fontSize: 10,
                                    margin: 0
                                });
                            @endfor
                        @endif
                    @endforeach
                @endforeach
            @endif
            @if($product != null)
                @for ($i = 1; $i <= $qty; $i++)
                    JsBarcode("#barcode{{ $product->id . $i }}", "{{ $product->product_code }}", {
                        format: "CODE128",
                        width: 1.5,
                        height: 40,
                        displayValue: true,
                        fontSize: 10,
                        margin: 0
                    });
                @endfor
            @endif
        });
    </script>
</body>
</html>
