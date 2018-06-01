<?php $quantity = 0; ?>
<li class="external">
    <h3>You have <span class="bold">{{ $rows['quantity'] }} items</span> in your cart</h3>
    <a href="{{ route('frontend.checkout') }}">view all</a>
</li>
<li>
    <ul class="dropdown-menu-list" style="height: 275px;" data-handle-color="#637283">
    @foreach($rows['orders'] as $row)
    <li>
        <a href="{{ route('frontend.designer.index', ['reload' => @$row['token']]) }}">
            <span class="photo"><img src="{{ has_image($row['image']) }}" alt=""> </span>
            <span class="subject">
                <span class="from"> {{ $row['name'] }} </span>
                <h5 class="pull-right">{{ amount_formatted($row['total_price']) }} </h5>
            </span>
            <span class="message"> {{ $row['quantity'] }} x {{ amount_formatted($row['unit_price']) }} </span>
        </a>
    </li>
    <?php $quantity += $row['quantity']; ?>
    @endforeach
    </ul>
</li>
<li><a href="{{ route('frontend.checkout') }}" class="btn-checkout">Checkout</a></li>
<div class="mini-cart-total" data-count="{{ $quantity }}"></div>


