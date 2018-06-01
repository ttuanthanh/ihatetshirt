<table style="max-width: 600;margin:0 auto;"">
    <tr>
        <td>
<?php $detail = json_decode($info->post_content); ?>
<table  border="1" width="100%" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th width="1"></th>
                <th width="250">Product</th>
                <th align="right">Price</th>
                <th align="right">QTY</th>
                <th align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail->orders as $order)
            <tr>
                <td width="90">
                    <img src="{{ has_image($order->image) }}" width="90"> 
                </td>
                <td width="100">{{ $order->name }}</td>
                <td align="right">{{ amount_formatted($order->unit_price) }}</td>
                <td align="right">{{ number_format($order->quantity) }}</td>
                <td align="right">{{ amount_formatted($order->total_price) }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
<br>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <td>
                
            <h3>Billing Address :</h3>

            <p><span class="text-muted">Address:</span><br>
            {{ ucwords($info->billing_firstname.' '.$info->billing_lastname) }}<br>
            {{ $info->billing_street_address_1 }}
            {{ $info->billing_street_address_2 }}
            {{ $info->billing_city }}
            {{ $info->billing_state }}
            {{ $info->billing_zip_code }}
            {{ countries($info->billing_country) }}
            </p>

            @if( $info->billing_company )
            <p><span class="text-muted">Company:</span><br>
            {{ $info->billing_company }}</p>
            @endif

            <p><span class="text-muted">Email address:</span><br>
            <a href="">{{ $info->billing_email }}</a></p>

            <p><span class="text-muted">Phone:</span><br>
            {{ $info->billing_telephone }}</p>

        </td>
        <td>

            <h3>Shipping Address :</h3>

            <p><span class="text-muted">Address:</span><br>
            {{ ucwords($info->shipping_firstname.' '.$info->shipping_lastname) }}<br>
            {{ $info->shipping_street_address_1 }}
            {{ $info->shipping_street_address_2 }}
            {{ $info->shipping_city }}
            {{ $info->shipping_state }}
            {{ $info->shipping_zip_code }}
            @if($info->shipping_country)
            {{ countries($info->shipping_country) }}
            @endif
            </p>

            @if( $info->shipping_company )
            <p><span class="text-muted">Company:</span><br>
            {{ $info->shipping_company }}</p>
            @endif

            <p><span class="text-muted">Email address:</span><br>
            <a href="">{{ $info->shipping_email }}</a></p>

            <p><span class="text-muted">Phone:</span><br>
            {{ $info->shipping_telephone }}</p>

        </td>
    </tr>
    <tr>
        <td>
            <p class="no-margin"><span class="text-muted">Shipping Method :</span><br> 
                <b class="text-primary">{{ @$post->find($info->shipping_method)->post_title }}</b><br>
                {{ date_formatted($info->shipping_date) }} 
            </p>   

            <p><span class="text-muted">Payment Method :</span><br> <b class="text-primary">{{ payment_method($info->payment_method) }}</b></p> 
            <p><span class="text-muted">Date Ordered :</span><br> <b class="text-primary">{{ date_formatted($info->order_date) }}</b></p>                     
        </td>
        <td>

            <table  border="0" width="100%" cellspacing="0" cellpadding="5">
                <tr class="sbold">
                    <td align="right"><strong>Sub Total :</strong> 
                    </td>
                    <td align="right" width="100"><strong>{{ amount_formatted($detail->subtotal) }}</strong></td>
                </tr>
                <tr>
                    <td align="right">
                    Shipment Fee : 
                    </td>
                    <td align="right">{{ amount_formatted($detail->shipping_charge) }}</td>
                </tr>
                <tr>
                    <td align="right">Discount :</td>
                    <td align="right">-{{ amount_formatted($detail->discount) }}</td>
                </tr>
                <tr class="sbold">
                    <td align="right"><strong>Total :</strong> </td>
                    <td align="right"><strong>{{ amount_formatted($detail->total) }}</strong></td>
                </tr>
            </table>    

        </td>
    </tr>
</table>
            
        </td>
    </tr>
</table>

