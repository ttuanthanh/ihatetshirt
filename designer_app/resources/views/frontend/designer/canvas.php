<div class="row">
    <div class="canvas_image image-builder ng-isolate-scope">
        <div class='fabric-container'>


            <div class="canvas-container-outer">
                <img src="<?php echo asset('assets/uploads/loaders/4.gif'); ?>" class="canvas-loader">
                <canvas fabric='fabric'></canvas>
            </div>

        </div>
    </div>
    <div class="canvas_sub_image">
        <ul>
            <li ng-repeat="prodImg in productImages" ng-show="prodImg.url">
                <img ng-click='loadProduct(defaultProductTitle, prodImg.url, defaultProductId, defaultPrice, defaultCurrency, $index, prodImg.attr, null)' data-ng-src="{{prodImg.url}}" alt="" width="120px;" id="subimg-{{$index}}" data-index="{{ $index }}">
                
            </li>
        </ul>
    </div>



    <div class="clearfix text-center">
            <span class="product_name">{{defaultProductTitle}}</span>

            <h4 class="text-muted" style="margin-top:20px">
                <div class="col-md-6 text-center d-colors" ng-repeat="prodImg in productImages" ng-show="prodImg.url">
                    <b class="uppercase text-primary">{{ prodImg.name }}</b> : <span class="t-{{ $index }}" data-name="{{ prodImg.name }}">0</span> color 
                </div>
            </h4>

    </div>

</div>

