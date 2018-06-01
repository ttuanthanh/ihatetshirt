@extends('layouts.admin')

@section('content')

<div class="row margin-top-20">

    <div class="col-md-6">

        <div class="portlet light bordered stats-portlet">

            <div class="row">
                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-basket-loaded text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> New Order</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    

                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="fa fa-calendar-check-o text-info"></i>    
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Order Today</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>                    

                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="fa fa-truck text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Ship Today</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    

            </div>


            <div class="row">
                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-pin text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Posts</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    

                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-users text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Customers</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    

                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-ghost text-info"></i>   
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Designs</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>                    
            </div>

            <div class="row">
                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-layers text-info"></i>  
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Products</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    
                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-picture text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Cliparts</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>    
                <a href="javascript:;" class="list-group-item col-md-4">
                    <div class="stat-box sbl">
                        <i class="icon icon-tag text-info"></i> 
                    </div>
                    <div class="stat-box sbr">
                        <h5 class="list-group-item-heading"> Coupons</h5>
                        <p class="list-group-item-text no-margin">0</p>     
                    </div>
                </a>                    
            </div>

        </div>
            

        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div id="container"></div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>    

    <div class="col-md-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered text-right">

            <small class="uppercase"><i class="no badge-cal"></i> Schedule Order </small>
            <small class="uppercase"><i class="pr badge-cal"></i> Artwork</small>
            <small class="uppercase"><i class="sr badge-cal"></i> Ship Date</small>


            <div class="portlet-body margin-top-10">
                <div id="calendar"></div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>    

</div>

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
}

</style>

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="../assets/global/plugins/moment.min.js" type="text/javascript"></script>

<!-- FULLCALENDAR -->
<link href="../assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
<script src="../assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>

<!-- HIGHCHARTS -->
<script src="../assets/plugins/highcharts/highcharts.src.js"></script>
<script src="../assets/plugins/highcharts/exporting.js"></script>
<!-- optional -->
<script src="../assets/plugins/highcharts/offline-exporting.js"></script>

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


Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Summary Report'
    },
    subtitle: {
        text: 'Statistics Report for 2017'
    },
    xAxis: {
        categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Summary Report'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0">₱ <b>{point.y:,.2f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Customer',
        data: [0]
    }, {
        name: 'Sales',
        data: [0]    }, {
        name: 'Order',
        data: [0]
    }]
});
</script>



@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
