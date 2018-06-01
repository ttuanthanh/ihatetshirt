<style>
.nav-tabs.nav-justified>li>a>.hidden-xs {
    top: calc(50% - 6px);
    position: inherit;
    display: block;
} 
.nav-tabs.nav-justified>li>a>.t-n-m {   
    top: auto;
}
</style>

<div class="containerx ng-scope margin-bottom-40" ng-controller="ProductCtrl" ng-app="productApp" id="productApp">
    <div ng-show="loading" class="loading">
        <h1 class="lodingMessage">Initializing Design Tool<img src="<?php echo asset('designer-tool/images/ajax-loader.gif'); ?>"></h1>
    </div>
    <div class="row clearfix" ng-cloak>
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 editor_section">

            <div id="content" class="">
                <ul class="nav nav-tabs uppercase">
                    <li class="active">
                        <a ng-click="deactivateAll()" href="#tab_1" data-toggle="tab" class="products" data-toggle="tab">
                        <span class="glyphicons t-shirt hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs">Products</span>
                        </a>
                    </li>
                    <li class="">
                        <a ng-click="deactivateAll()" href="#tab_2" data-toggle="tab" class="graphics">
                        <span class="glyphicons picture hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs">Design</span>
                        </a>
                    </li>

                    <li class="tab-name-number">
                        <a ng-click="deactivateAll()" href="#tab_4_2" data-toggle="tab"  class="text">
                        <span class="glyphicon glyphicon-text-background hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs t-n-m">Name & Number</span>
                        </a>
                    </li>

                    <li class="">
                        <a ng-click="layers()" data-toggle="tab">
                        <span class="glyphicons comments hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs">Layers</span>
                        </a>
                    </li>

                    <li class="">
                        <a ng-click="deactivateAll()" href="#tab_4" data-toggle="tab">
                        <span class="glyphicons comments hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs">Comment</span>
                        </a>
                    </li>
                    <li class="">
                        <a ng-click="deactivateAll()" href="#tab_5" data-toggle="tab">
                        <span class="glyphicons floppy_saved hidden-lg hidden-md hidden-sm"></span> 
                        <span class="hidden-xs">Publish</span>
                        </a>
                    </li>
                </ul>
                
                @include('frontend.designer.text-control')

                <div id="content" class="tabing">
                    <div id="my-tab-content" class="tab-content action_tabs">
                        <div class="tab-pane active" id="tab_1">
                        @include('frontend.designer.product')
                        </div>

                        <div class="tab-pane" id="tab_2">
                        @include('frontend.designer.graphics')
                        </div>

                        <div class="tab-pane" id="tab_4_2">
                        @include('frontend.designer.name-number')
                        </div>

                        <div class="tab-pane" id="tab_4">
                        @include('frontend.designer.connect')
                        </div>

                        <div class="tab-pane" id="tab_5">
                        @include('frontend.designer.publish')
                        </div>

                        <div class="tab-pane clearfix" id="Layers">
                        @include('frontend.designer.layers')
                        </div>
                    </div>
                </div>

                <div class="panel-steps margin-top-20 uppercase">
                    <div class="panel-step text-center"><span>Pick your<br> garments</span></div>
                    <div class="panel-step"><span class="fa fa-chevron-right"></span></div>                                        
                    <div class="panel-step text-center">Have your<br> masterpiece</div>
                    <div class="panel-step"><span class="fa fa-chevron-right"></span></div>                                        
                    <div class="panel-step text-center">Special<br> Request</div>
                    <div class="panel-step"><span class="fa fa-chevron-right"></span></div>                                        
                    <div class="panel-step text-center">Share /<br> Quote</div>                                        
                </div>

                <hr>

                <?php $tips = $setting->get_setting('designer_tips'); ?>
                @if( $tips )
                <h4>TIPS</h4>
                @endif

                <div class="row">
                    <div class="col-md-9">
                    @if( $tips )
                        <p class="text-justify small no-margin margin-bottom-20">{{ $tips }}</p>
                    @endif
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="javascript::void(0)" class="btn-get-quote btn btn-block btn-publish">Get<br> Quote</a>                                    
                    </div>
                </div>
                
            </div>

        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 canvas_section pull-right">
            <!-- BEGIN EDITOR SECTION -->
            @include('frontend.designer.canvas')
            <!-- END EDITOR SECTION -->         
        </div>
    </div>
</div>

