@extends('templates.fullwidth')

@section('content')
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    My Designs
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content bg-white">
    <div class="container">

        <form action="" method="post" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.sidebar')
                </div>
                <div class="col-md-9">

                @include('notification')

                <div class="form-bg-1x">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="1">Details</th>
                        <th width="300"></th>
                        <th width="100">Size</th>
                        <th width="1">Status</th>
                        <th class="text-center" width="120">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                    <?php $content = json_decode($row->post_content); ?>
                    <tr class="tr-row-lg has-row-actions">
                        <td width="1">                  
                            <a href="{{ asset(@$content->image) }}" class="btn-img-preview" data-title="{{ @$content->details->color_title }}">
                                <img src="{{ asset(@$content->image) }}"> 
                            </a>
                        </td>
                        <td>
                            <div class="order-status">
                                <p>{{ $row->post_title }}</p>
                                <span class="box-color" style="background-color: {{ @$content->details->color }};"></span> {{ @$content->details->color_title }}
                            </div>
                            <div class="row-actions">
                                <a href="{{ URL::route('frontend.account.view-design', $row->id) }}">View</a> | <a href="{{ URL::route('frontend.designer.index', ['reload' => $row->post_name]) }}">Edit</a>               
                            </div>
                        </td>
                        <td>
                            
                        </pre>                        
                            <?php $sizes = @$content->start_design->sizes; ?>
                            @if( $sizes )
                            <div class="order-status">
                            @foreach($sizes as $size_k => $size_v)
                                @if($size_v)
                                {{ $size_k }} ( <span class="text-muted"><b>{{ $size_v }}</b></span> ) <br>
                                @endif
                            @endforeach
                            </div>
                            @endif
                        </td>
                        <td>{{ status_ico($row->post_status) }}</td>
                        <td class="text-center">
                            {{ date_formatted($row->created_at) }}<br>
                            <span class="text-muted">{{ time_ago($row->created_at) }}</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            
            {{ $rows->links() }}

            @if( ! count($rows) )
                <div class="well">No customer designs found.</div>
            @endif

            </div>

                </div>
            </div>

        </form>
                
    </div>
</div>

@endsection

@section('style')
<style>
table {
    font-size: 14px;
}
.box-color {
    height: 20px;
    width: 20px;
    display: inline-block;
    border: 1px solid #9E9E9E;
    border-radius: 3px;
    vertical-align: bottom;
}    
.row-actions {
    margin-top: 5px;
    font-size: 12px;
    text-transform: uppercase;
    display: none;
}
.has-row-actions:hover .row-actions {
    display: block;
} 
.tr-row-lg {
    height: 130px;
}
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
@stop

