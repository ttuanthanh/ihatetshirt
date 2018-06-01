<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">{{ $info->post_title }}</h4>
    </div>
    <div class="modal-body">

    <div style="display:none;" class="start-note">
        <h4 class="well text-center">Please <a href="" class="sbold start-d"  data-dismiss="modal">start your design</a> to save your artwork and get prices.</h4>    
    </div>

    <?php $reloaded = (@$design_title && @$email) ? 1 : 0;  ?>

    <div class="customer-details">
        <div class="row">
            <div class="col-md-6">
                <label>Name your design:</label>                    
                <input type="text" id="design_title" class="form-control" value="{{ @$design_title }}">  
                <span class="text-danger error-msg error-design_title"></span>          
            </div>
            <div class="col-md-6">
                <label>Your email address:</label>                    
                <input type="text" id="email" class="form-control" value="{{ @$email }}">
                <span class="text-danger error-msg error-email"></span>          
            </div>
        </div>

        <div class="form-group margin-top-20">
            <a href="javascript:;" class="btn btn-lg blue btn-save ng-scope" onclick="angular.element('#productApp').scope().saveDesign('pre-save')"><i class="fa fa-check"></i> Save</a>
        </div>    
    </div>


    <div class="cal-form" style="{{ $reloaded ? '' : 'display:none;' }}">
    <form action="{{ route('frontend.get-quote') }}" id="form-product" method="post">
        
        <input type="hidden" name="pid" value="{{ $start_design['inputs']['pid'] }}">
        <input type="hidden" name="type" value="{{ $start_design['inputs']['type'] }}">
        <input type="hidden" name="image" value="{{ $start_design['inputs']['image'] }}">
        <input type="hidden" name="color_index" value="{{ $start_design['inputs']['color_index'] }}">
        <input type="hidden" name="color_hex" value="{{ $start_design['inputs']['color_hex'] }}">
        <input type="hidden" name="color_title" value="{{ $start_design['inputs']['color_title'] }}">

        <div class="row">
            <div class="col-md-6">
                <p><h5 class="uppercase small no-margin text-muted">Screen printing</h5> <strong>Front</strong>: <span class="sp-0">0</span> color / <strong>Back</strong> <span class="sp-1">0</span> color</p>                
            </div>
            <div class="col-md-6">
                <p><h5 class="uppercase small no-margin text-muted">Color</h5> {{ $start_design['inputs']['color_title'] }}
                    <span class="color-box" style="background-color: {{ $start_design['inputs']['color_hex'] }};"></span>
                </p>                
            </div>
        </div>



        
        <hr>

    

    <p class="msg-quantity text-danger error-msg"></p>
        <h5 class="sbold">Enter sizes to calculate your price: </h5>
        
        <div class="row">
            <div class="col-md-9">


<div class="row">
    <div class="col-md-12">
        @if( @$info->size )
        <?php $attr_size = $start_design['inputs']['size']; ?>
        @foreach(json_decode($info->size) as $s_k => $size)
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="form-group">
            {{ $size->name }}
            <input type="number" name="size[]" class="form-control numeric size-price" 
            min="0" 
            value="{{ @$attr_size[$s_k] }}">                 
            </div>
        </div>
        @endforeach 
        @endif        
    </div>

    
</div>

            </div>
            <div class="col-md-3 text-center">
                <h5>Total QTY</h5>
                <h2 class="sbold no-margin text-primary total-qty">0</h2>
            </div>
        </div>


        <p class="text-muted text-center margin-top-20">Tip: (Save more by increasing qty or reduce number of colors on design)</p>


    <input type="hidden" name="front_color" class="sp-0">   
    <input type="hidden" name="back_color" class="sp-1">      


    </form>         

    <hr>

    <div class="row">
        <div class="col-md-7">
            <div class="text-center">
                <h3>All-inclusive Prices:</h3>
                <strong>Free Shipping</strong> deliveried by<strong class="text-primary">
                <?php 
                    $date = strtotime("+12 day", strtotime(date('Y-m-d')));
                    echo date("D, M d", $date);
                ?>                    
                </strong><br>

                or <strong>Rush</strong> by 
                <strong class="text-primary"><?php 
                    $date = strtotime("+6 day", strtotime(date('Y-m-d')));
                    echo date("D, M d", $date);
                ?></strong>       
                apply at checkout
            </div>                
        </div>
        <div class="col-md-5">
            <button type="button" class="btn btn-primary btn-lg btn-block margin-top-30 btn-calculate">Calculate</button>    
        </div>
    </div>

    <div class="result" style="display:none;">
    <hr>

        <div class="text-center">
            <div class="row">
                <div class="col-md-5">
                    <h4 class="sbold">Need rush shipping?</h4>
                    <p>Upgrade shipping at check out</p>
                    to receive it by: <strong class="text-primary">
                    <?php 
                        $date = strtotime("+6 day", strtotime(date('Y-m-d')));
                        echo date("D, M d", $date);
                    ?>      
                    </strong>        
                </div>
                <div class="col-md-3 text-center">
                    <small>Unit Price</small>
                    <h3 class="sbold no-margin text-primary unit-price"></h3>
                </div>
                <div class="col-md-4 text-center">        
                    <small>Total Price</small>
                    <h3 class="sbold no-margin text-primary total-price"></h3>            
                </div>
            </div>
        </div>


        <div class="form-group margin-top-20">
            <div class="btn-group btn-group-justified">
                <a href="#" class="btn btn-lg blue btn-save ng-scope" onclick="angular.element('#productApp').scope().saveDesign('save')"><i class="fa fa-check"></i> Save</a>

                <a href="#" class="btn btn-lg green btn-buy ng-scope" onclick="angular.element('#productApp').scope().saveDesign('add')"><i class="fa fa-cart-plus"></i> Buy Now</a>
            </div>                
        </div>      

    </div>

    </div>

    <div class="text-center margin-top-20 sbold publish-loader" style="display:none;">
         <img src="<?php echo asset('assets/uploads/loaders/3.gif'); ?>"> Please wait while processing ...          
    </div>

    </div>

</div>
