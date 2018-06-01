@extends('templates.fullwidth')

@section('content')

<div class="page-title"><div class="container"><div class="row"><div class="col-md-12"><h1>
        Thank you for your order.
        <span style="float:right">Order # {{ $info->id }}</span>
        </h1></div></div></div></div>

<div class="section featured-designs">
    <div class="container bg-white" style="padding-top: 25px;">

        <p class="alert alert-info">Your order has been recieved and is now being processed. Your order details are shown below for your reference:</p>

        <?php $detail = json_decode($info->post_content); ?>

        <div class="row">
            <div class="col-md-3">
                <h5>Billing Address :</h5>
                <hr>
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
                    
            </div>
            <div class="col-md-3" style="border-left: 1px solid #cacaca;">
                <h5>Shipping Address :</h5>
                <hr>

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
                    
            </div>

            <div class="col-md-6">

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="70"></th>
                        <th width="250">Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail->orders as $order)
                    <tr>
                        <td>
                            <img src="{{ has_image($order->image) }}" class="img-responsive img-thumb"> 
                        </td>
                        <td>{{ $order->name }}</td>
                        <td class="text-right">{{ amount_formatted($order->unit_price) }}</td>
                        <td class="text-right">{{ number_format($order->quantity) }}</td>
                        <td class="text-right">{{ amount_formatted($order->total_price) }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="row">
                <div class="col-md-6">

                    <p class="no-margin"><span class="text-muted">Shipping Method :</span><br> 
                        <b class="text-primary">{{ @$post->find($info->shipping_method)->post_title }}</b><br>
                        {{ date_formatted($info->shipping_date) }} 
                    </p>  
                    
                    <p><span class="text-muted">Payment Method :</span><br> <b class="text-primary">{{ payment_method($info->payment_method) }}</b></p> 
                    <p><span class="text-muted">Date Ordered :</span><br> <b class="text-primary">{{ date_formatted($info->order_date) }}</b></p> 

                </div>
                <div class="col-md-6">

                    <table width="100%">
                        <tr class="sbold">
                            <td class="text-right"><strong>Sub Total :</strong> 
                            </td>
                            <td class="text-right" width="100"><strong>{{ amount_formatted($detail->subtotal) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-right">
                            Shipment Fee : 
                            </td>
                            <td class="text-right">{{ amount_formatted($detail->shipping_charge) }}</td>
                        </tr>
                        <tr class="text-danger">
                            <td class="text-right">Discount :</td>
                            <td class="text-right">-{{ amount_formatted($detail->discount) }}</td>
                        </tr>
                        <tr class="sbold">
                            <td class="text-right"><strong>Total :</strong> </td>
                            <td class="text-right"><strong>{{ amount_formatted($detail->total) }}</strong></td>
                        </tr>
                    </table>    
                            
                </div>
            </div>  

            

            </div>
        </div>

    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
