@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }}</h1>
<!-- END PAGE TITLE-->

@include('notification')

@include('admin.settings.tab')


<form class="form-horizontal" method="post">
{{ csrf_field() }}
<div class="portlet light bordered">
    <div class="portlet-body">

        <div class="tab-content">

            <div class="row">
                <div class="col-md-4">

                    <div class="form-group">
                        <div class="col-md-6 control-label">Shipping fee (per box)</div>
                        <div class="col-md-6">             
                            <input type="text" class="form-control numeric text-right" 
                            name="pricing[shipping_box_fee]" 
                            value="{{ $pricing['shipping_box_fee'] }}" 
                            maxlength="7"> 
                        </div>
                    </div>
                        
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>No. of box</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(range(0, 26) as $shopping_box)
                            <tr>
                               <td>
                                    <input type="text" class="form-control numeric text-right" 
                                    name="pricing[shipping_box][{{$shopping_box}}][from]" 
                                    value="{{ $pricing['shipping_box'][$shopping_box]['from'] }}" 
                                    maxlength="7"> 
                               </td>
                               <td>
                                    <input type="text" class="form-control numeric text-right" 
                                    name="pricing[shipping_box][{{$shopping_box}}][to]" 
                                    value="{{ $pricing['shipping_box'][$shopping_box]['to'] }}" 
                                    maxlength="7"> 
                               </td>
                               <td>
                                    <input type="text" class="form-control numeric text-right" 
                                    name="pricing[shipping_box][{{$shopping_box}}][box]" 
                                    value="{{ $pricing['shipping_box'][$shopping_box]['box'] }}" 
                                    maxlength="7"> 
                               </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="col-md-8">

                    <div class="portlet light bordered tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab" aria-expanded="true"> Front Print Location </a>
                            </li>
                            <li class="">
                                <a href="#tab_2" data-toggle="tab" aria-expanded="false"> Back Print Location </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">

                                <h3>FRONT PRINT LOCATION</h3>

                                <table class="table table-bordered margin-top-20">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>1 Color</th>
                                            <th>2 Colors</th>
                                            <th>3 Colors</th>
                                            <th>4 Colors</th>
                                            <th>5 Colors</th>
                                            <th>6 Colors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(range(0, 17) as $front_location)
                                        <tr>
                                           <td>
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[front_location][{{$front_location}}][from]" 
                                                value="{{ @$pricing['front_location'][$front_location]['from'] }}" 
                                                maxlength="7"> 
                                           </td>
                                           <td>
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[front_location][{{$front_location}}][to]" 
                                                value="{{ @$pricing['front_location'][$front_location]['to'] }}" 
                                                maxlength="7"> 
                                           </td>
                                           @foreach(range(1, 6) as $color)
                                           <td>
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[front_location][{{$front_location}}][color][{{$color}}]" 
                                                value="{{ @$pricing['front_location'][$front_location]['color'][$color] }}" 
                                                maxlength="7"> 
                                           </td>
                                           @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                            <div class="tab-pane" id="tab_2">
                               
                                <h3>BACK PRINT LOCATION</h3>

                                
                                <table class="table table-bordered margin-top-20">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>1 Color</th>
                                            <th>2 Colors</th>
                                            <th>3 Colors</th>
                                            <th>4 Colors</th>
                                            <th>5 Colors</th>
                                            <th>6 Colors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(range(0, 17) as $back_location)
                                        <tr>
                                           <td>
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[back_location][{{$back_location}}][from]" 
                                                value="{{ @$pricing['back_location'][$back_location]['from'] }}" 
                                                maxlength="7"> 
                                           </td>
                                           <td>
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[back_location][{{$back_location}}][to]" 
                                                value="{{ @$pricing['back_location'][$back_location]['to'] }}" 
                                                maxlength="7"> 
                                           </td>
                                           @foreach(range(1, 6) as $color)
                                           <td>                        
                                                <input type="text" class="form-control numeric text-right" 
                                                name="pricing[back_location][{{$back_location}}][color][{{$color}}]" 
                                                value="{{ @$pricing['back_location'][$back_location]['color'][$color] }}" 
                                                maxlength="7"> 
                                           </td>
                                           @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                </div>
            </div>

   

        </div>

    </div>
</div>

<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button> 
</div>

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
