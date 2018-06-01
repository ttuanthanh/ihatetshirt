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

            <!-- BEGIN TAB CONTENT -->
            <div class="table-responsive margin-top-10">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Default</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( payment_method() as $payment_k => $payment_v)
                        <input type="hidden" name="payment[{{ $payment_k }}][value]" value="{{ $payment_v }}">
                        <tr>
                            <td>
                                <h5 href="" class="sbold">{{ $payment_v }}</h5>
                            </td>
                            <td>
                            <div class="mt-radio-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="radio" name="payment[default]" value="{{ $payment_k }}" {{ checked(@$payment->default, $payment_k) }}>
                                    <span></span> Set as default
                                </label>
                            </div>                                
                            </td>
                            <td>
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="payment[{{ $payment_k }}][status]" value="1"{{ checked(@$payment->$payment_k->status, 1) }}}>
                                    <span></span> Enabled
                                </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!-- END TAB CONTENT -->

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
