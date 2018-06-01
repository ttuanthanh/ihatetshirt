@extends('layouts.admin')

@section('content')

<h1 class="page-title"> Dashboard
</h1>

<div class="row margin-top-20">

    <div class="col-md-6">

        <div class="portlet light bordered stats-portlet">

            <div class="row">
                <a href="{{ route('admin.orders.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-basket-loaded text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> New Order</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_new_order) }}</p>     
                    </div>
                </a>    

                <a href="{{ route('admin.orders.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="fa fa-calendar-check-o text-info"></i>    
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Order Today</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_order_today) }}</p>     
                    </div>
                </a>                    

                <a href="{{ route('admin.orders.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="fa fa-truck text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Ship Today</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_ship_today) }}</p>     
                    </div>
                </a>    

            </div>


            <div class="row">
                <a href="{{ route('admin.posts.index', ['post_type' => 'post']) }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-pin text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Posts</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_posts) }}</p>     
                    </div>
                </a>    

                <a href="{{ route('admin.customers.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-users text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Customers</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_customers) }}</p>     
                    </div>
                </a>    

                <a href="{{ route('admin.customers.designs.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-ghost text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Designs</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_designs) }}</p>     
                    </div>
                </a>                    
            </div>

            <div class="row">
                <a href="{{ route('admin.products.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-layers text-info"></i>  
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Products</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_products) }}</p>     
                    </div>
                </a>    
                <a href="{{ route('admin.cliparts.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-picture text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Cliparts</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_cliparts) }}</p>     
                    </div>
                </a>    
                <a href="{{ route('admin.coupons.index') }}" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-tag text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Coupons</h5>
                        <p class="list-group-item-text no-margin">{{ number_format($c_coupons) }}</p>     
                    </div>
                </a>                    
            </div>

        </div>

        <div class="portlet light bordered">
            <h4>{{ App\Setting::get_setting('site_title') }}</h4>

            <p><b>Online solution for printing</b><br>
            Website: <a href="{{ url('/') }}">{{ url('/') }}</a><br>
            Email: <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a>
            </p>
        </div>


    </div>    

    <div class="col-md-6">
        <div class="portlet light bordered">
            <div id="calendar"></div>
        </div>
    </div>    

</div>





@endsection

@section('style')
<style>
.badge-cal { 
    display: inline-block;
    height: 10px;
    width: 10px;
    border-radius: 10px;
    margin: 0 10px 0;
}
.no {
    background: #0023e6;    
}
.pr {
    background: #ff0000;    
}
.sr {
    background: #4CAF50;    
}
.dashboard-stat {
    border-right: 3px solid #bbbbbb;
    border-bottom: 3px solid #bbbbbb;
    border-radius: 10px;    
    cursor: default!important;
}
.link-stat:hover {    
    opacity: 0.7;
    cursor: pointer!important;
}
.list-group-item:hover .text-info, .list-group-item:hover .list-group-item-text {
    color: #659be0;
}
.stats-portlet {
    padding: 0px 15px 0px !important;
}
.stat-box {
    display: inline-block;
    vertical-align: middle;
}
.sbl .fa, .sbl .icon {
    font-size: 1.5em;
    margin: 0 15px 0 0;
    position: absolute;
    right: 0;
}
.fc-unthemed .fc-today {
    background: #ffeb82 !important;
}
.text-info {
    color: #cecece;
}
.list-group-item-heading {
    text-transform: uppercase;
}
.list-group-item-text {
    font-weight: bold;
    color: #cecece;
    font-size: 1.2em;    
}
</style>

@stop

@section('plugin_script') 

    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="../assets/global/plugins/moment.min.js" type="text/javascript"></script>

    <!-- FULLCALENDAR -->
    <link href="../assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <script src="../assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>


@stop

@section('script')

<script>
jQuery(document).ready(function() {    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var h = {};

    $('#calendar').fullCalendar({ 
        defaultView: 'month', 
        editable: false,
        droppable: false,
        events: []    
    });
});    

</script>
@stop
