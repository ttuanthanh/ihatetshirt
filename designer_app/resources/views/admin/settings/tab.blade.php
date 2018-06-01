<div class="tabbable-custom nav-justified">
<ul class="nav nav-tabs  uppercase">
    <li class="{{ actived(Request::segment(3), 'general') }}">
        <a href="{{ URL::route('admin.settings.general') }}">General Settings </a>
    </li>
    <li class="{{ actived(Request::segment(3), 'price-table') }}">
        <a href="{{ URL::route('admin.settings.price-table') }}">Price Table </a>
    </li>
    <li class="{{ actived(Request::segment(3), 'designer') }}">
        <a href="{{ URL::route('admin.settings.designer') }}">Designer </a>
    </li>
    <li class="{{ actived(Request::segment(3), 'shop') }}">
        <a href="{{ URL::route('admin.settings.shop') }}">Shop Settings </a>
    </li>
    <li class="{{ actived(Request::segment(2), 'colors') }}">
        <a href="{{ URL::route('admin.colors.index') }}">Colors </a>
    </li>
    <li class="{{ actived(Request::segment(2), 'shipping-methods') }}">
        <a href="{{ URL::route('admin.shipping-methods.index') }}">Shipping Methods</a>
    </li>
    <li class="{{ actived(Request::segment(3), 'payments') }}">
        <a href="{{ URL::route('admin.settings.payments') }}">Payment Methods </a>
    </li>  
    <li class="{{ actived(Request::segment(3), 'emails') }}">
        <a href="{{ URL::route('admin.settings.emails', ['tab' => 'register']) }}">Emails </a>
    </li>       
</ul>
</div>


