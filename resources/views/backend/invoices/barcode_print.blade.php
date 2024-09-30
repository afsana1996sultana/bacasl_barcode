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
    </style>
</head>
<body>
    <div>
        <div>
            <div style="margin-top: -10px">
                @if($productstock != null)
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
                    <p style="text-align: center; font-weight:bold; font-size:20px;">{{ get_setting('site_name')->value }}</p>
                    <p style="text-align: center; font-weight:bold; font-size:20px;">Price: {{ $amount }}</p>
                    <p style="text-align: center; font-weight:bold; font-size:20px;" class="show">Size: {{$productstock->varient}}</p>
                    <p style="text-align: center; font-weight:bold; font-size:20px;" class="show">Code: {{$productstock->stock_code}}</p>
                    <p class="barcode_img"><svg id="barcode1"></svg></p>
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
                    <p style="text-align: center; font-size:20px; font-weight:bold">{{ get_setting('site_name')->value }}</p>
                    <p style="text-align: center; font-size:20px; font-weight:bold">Price: {{ $amount }}</p>
                    <p style="text-align: center; font-size:20px; font-weight:bold">Code: {{$product->product_code }}</p>
                    <p class="barcode_img"><svg id="barcode2"></svg></p>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <script>
        window.onload = function() {
            window.print();
        };
        document.addEventListener("DOMContentLoaded", function() {
            @if($productstock != null)
                JsBarcode("#barcode1", "{{ $productstock->stock_code }}", {
                    format: "CODE128",
                    width: 2,
                    height: 60,
                    displayValue: true
                });
            @endif
            @if($product != null)
                JsBarcode("#barcode2", "{{ $product->product_code }}", {
                    format: "CODE128",
                    width: 2,
                    height: 60,
                    displayValue: true
                });
            @endif
        });
    </script>
</body>
</html>
