'use strict';
angular.module('productApp', [
    'common.fabric',
    'common.fabric.utilities',
    'common.fabric.constants',
    'colorpicker.module',
    'ngSanitize',
    'ngMaterial',
    'ngScrollbar',
    'ngFileUpload'
]).controller('ProductCtrl', [
    '$scope',
    'Fabric',
    'FabricConstants',
    'Keypress',
    '$http',
    '$timeout',
    '$mdDialog',
    '$mdToast',
    'Upload',
    function ($scope, Fabric, FabricConstants, Keypress, $http, $timeout, $mdDialog, $mdToast, Upload) {
        $scope.fabric = {};
        $scope.status = '  ';

        var _this = this;
        var last = {
            bottom: true,
            top: false,
            left: false,
            right: true
        };

        $scope.loadKey = [0];
        var reload = false;

        $scope.NOTIFICATION_MESSAGES = [];
        $scope.REQUEST_URL = [];

        $scope.products = [];
        $scope.productImages = [];
        $scope.graphics = [];
        $scope.objectLayers = [];
        $scope.isloaded = true;
        $scope.alertMessage = '';
        $scope.activeDesignObject = 0;
        $scope.isMenuClicked = true;
        $scope.enter_drawing_status = false;
        $scope.FabricConstants = FabricConstants;

        $scope.colors = [];

        $scope.changeColorScheme = function () {
            $http({
                method: 'post',
                url:$scope.REQUEST_URL.LOAD_COLOR_SCHEME,
                data: {
                    primaryColor: $scope.primaryColor,
                    secondaryColor: $scope.secondaryColor
                },
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: _this.transformRequest
            }).success(function (data, status, headers, config) {
                $('.css_gen').html(data);
            }).error(function (data, status, headers, config) {
                $scope.isloaded = false;
                $scope.$broadcast("AjaxCallHappened",false);
            });
            $(".customizer").removeClass('customizer_toggle');
            $("body").removeClass('body_overlay');
        };

        $scope.enterDrawingMode = function(){
            if($scope.fabric.checkBackgroundImage()){
                if($scope.fabric.toggleDrawing() == 'Cancel'){
                    $scope.enter_drawing_mode = 'Cancel Drawing Mode';
                    $scope.enter_drawing_status = true;
                }else{
                    $scope.enter_drawing_mode = 'Enter Drawing Mode';
                    $scope.enter_drawing_status = false;
                    $scope.fabric.resetBrush($scope.drawing_mode_selector, $scope.drawing_color, $scope.drawing_line_width, $scope.drawing_line_shadow);
                }
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.enterDrawing = function(){
            if($scope.fabric.checkBackgroundImage()){
                
                $scope.fabric.enterDrawing();
                $scope.enter_drawing_mode = 'Cancel Drawing Mode';
                $scope.enter_drawing_status = true;
                
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.exitDrawing = function(){
            if($scope.fabric.checkBackgroundImage()){
                
                $scope.fabric.exitDrawing();
                $scope.enter_drawing_status = true;
                
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.changeDrawingMode = function(mode){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.changeDrawingMode(mode, $scope.drawing_color, $scope.drawing_line_width, $scope.drawing_line_shadow);
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.fillDrawing = function(color){
            if($scope.fabric.checkBackgroundImage()){
                $scope.drawing_color = color;
                $scope.fabric.fillDrawing(color);
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.changeDrawingWidth = function(width){
            if($scope.fabric.checkBackgroundImage()){
                $scope.drawing_line_width = width;
                $scope.fabric.changeDrawingWidth(width);
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.changeDrawingShadow = function(shadow){
            if($scope.fabric.checkBackgroundImage()){
                $scope.drawing_line_shadow = shadow;
                $scope.fabric.changeDrawingShadow(shadow);
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.makeCode = function(elText) {
            var qrcodesvg 	= new Qrcodesvg( elText, $scope.qrCode, 150);
            qrcodesvg.draw();
        };

        $scope.addShape = function (path) {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.addShape(path);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();

            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.addUploadImage = function (image) {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.addUploadImage(image);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
                $('#edit-colors').modal('show');
                $('.screen-img').attr('src', image);

                setTimeout(function () {
                    $('.img-upload').attr('src', image);
                }, 500);

                $('#screen-colors li span').removeClass('selected');
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.onFileSelect = function(type) {

                if ($scope.fabric.checkBackgroundImage()) {

                    var assets_url = $('[name="app-url"]').attr('content');

                    if(type=='confirm') {
                        $('#graphic-remove-bg').modal('show');
                        return false;                            
                    }

                    $('[name=bg]').val(type);
                    $('#graphic-remove-bg').modal('hide');

                    var loader = '<div class="img-thumb graphic-loader" style="display:none;">'
                        + '<a href="javascript:void(0);"><img src="'+assets_url+'assets/uploads/loaders/4.gif"></a>'
                        + '</div>';    
                    
                    $('.graphic-list').append(loader);
                    $('.msg-graphic').html('');

                    var formElement = document.querySelector("#upload-image");
                    $('.graphic-loader').show();
                    $.ajax({
                        url: $scope.REQUEST_URL.UPLOAD_IMAGE, // Url to which the request is send
                        type: "POST",             // Type of request to be send, called as method 
                        data: new FormData( formElement ), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        contentType: false,       // The content type used when sending data to the server.
                        cache: false,             // To unable request pages to be cached
                        processData:false,        // To send DOMDocument or non processed data file it is set to false
                        success: function(res)   // A function to be called if request succeeds
                        {
                            var data = JSON.parse(res);
           
                            if(data.error) {
                                
                                $.each( data.msg.file, function( key, value ) {
                                    $('.msg-graphic').append('<li>'+value+'</li>');
                                });

                                $('.graphic-loader').remove();
                                return false;
                            }

                            $scope.fabric.addImage(data.msg);
                                
                            var thumb = '<div class="upload-thumb ng-scope">'
                                + '<a href="javascript:void(0);" onclick="angular.element(\'#productApp\').scope().addUploadImage(\''+data.msg+'\')" tabindex="0">'
                                + '<img src="'+data.msg+'"></a>'
                                + '</div>';

                            $('.graphic-list').append(thumb);
                            $('.screen-img').attr('src', data.msg);

                            $('#screen-colors li span').removeClass('selected');
                            $('.graphic-loader').remove();

                            setTimeout(function () {
                                $('#edit-colors').modal('show');
                            }, 500);

                            setTimeout(function () {
                                $('.img-upload').attr('src', data.msg);
                            }, 1000);


                        }
                    });



                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();
                } 

        };

        $scope.clearAll = function () {

            var confirm = $mdDialog.confirm()
                .title('')
                .textContent('Are you sure you want to clear canvas? This will remove all design elements you have created.')
                .ariaLabel('Confirm')
                .ok('Ok')
                .cancel('Cancel');
            $mdDialog.show(confirm).then(function() {
                $scope.fabric.clearCanvas();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();

            });
        };

        $scope.clearCanvas = function (){

                $scope.fabric.clearCanvas();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();

        };

        $scope.removeSelectedObject = function () {

            var confirm = $mdDialog.confirm()
                .title('')
                .content('Are you sure you want to remove selected layer? this will remove the selected layer/design element.')
                .ariaLabel('Confirm')
                .ok('Ok')
                .cancel('Cancel');
            $mdDialog.show(confirm).then(function() {
                $scope.fabric.deleteActiveObject();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();

            });
        };

        $scope.deactivateAll = function () {
            $scope.fabric.deactivateAll();
            $scope.exitDrawing();
            $scope.objectLayers = [];
            $scope.objectLayers = $scope.fabric.canvasLayers();
            $scope.$broadcast('rebuild:me');
        };

        $scope.verticalAlign = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.centerV();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }

        };

        $scope.horizontalAlign = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.centerH();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }

        };

        $scope.backwordSwap = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.sendBackwards();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }

        };

        $scope.objectBackwordSwap = function (object) {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.objectSendBackwards(object);
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }

        };

        $scope.forwardSwap = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.bringForward();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.objectForwardSwap = function (object) {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.objectBringForward(object);
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.layers =  function () {

            $scope.deactivateAll();

            var  activeTab = $('#tabs').find('li.active');
            $("#my-tab-content > div.active").removeClass('active');
            $(activeTab).removeClass('active');
            $('#Layers').addClass('active');
            $scope.objectLayers = [];
            $scope.objectLayers = $scope.fabric.canvasLayers();
            $scope.$broadcast('rebuild:layer');

        };

        $scope.$on('scrollbar.show', function(){ 
        });

        $scope.$on('scrollbar.hide', function(){ 
        });

        $scope.copyItem =  function () {
            if($scope.fabric.checkBackgroundImage()){
                if($scope.fabric.copyItem() == 'DONE') {
                    _this.showNotification('Object Copied!', false);
                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();
                }
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.pasteItem =  function () {
            if($scope.fabric.checkBackgroundImage()){
                if($scope.fabric.pasteItem() == 'DONE') {
                    _this.showNotification('Object Paste!', false);
                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();
                }
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.addQRCode = function(text){
            if($scope.fabric.checkBackgroundImage()){
                if(typeof text != "undefined") {
                    $scope.makeCode(text);
                    setTimeout(function () {
                        var srcVal = $('#' + $scope.qrCode).html();
                        $scope.fabric.addShapeString(srcVal);
                        $('#' + $scope.qrCode).html('');
                        $scope.objectLayers = [];
                        $scope.objectLayers = $scope.fabric.canvasLayers();
                    }, 500);
                }
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.addText = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.addText($scope.fabric.selectedObject.text);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        
        $scope.toggleTeamFont = function (font, id) {
            $scope.fabric.toggleTeamFont(font, id);
        };
        $scope.addNameNumber = function (name, color, font) {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.addNameNumber(name, color, font);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.removeNameNumber = function (name) {
            $scope.fabric.removeNameNumber(name);
        }
        
        $scope.clipartFill = function (target, color) {
            $scope.fabric.clipartFill(target, color);
        };
        
        $scope.nameNumberFill = function (color, target) {
            $scope.fabric.nameNumberFill(color, target);
        };

        $scope.addTextByAction = function () {
            
            $scope.deactivateAll();

            if($scope.fabric.checkBackgroundImage() && $scope.isMenuClicked){
                $scope.fabric.addText();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
                $scope.isMenuClicked = false;
            }
        };

        $scope.addWordCloud = function () {
            if($scope.fabric.checkBackgroundImage()){
                if(typeof $scope.fabric.selectedObject.textWordCloud != "undefined" && $scope.fabric.selectedObject.textWordCloud != '') {
                    $scope.fabric.addWordCloud($scope.fabric.selectedObject.textWordCloud);
                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();
                }else{
                    _this.showNotification($scope.NOTIFICATION_MESSAGES.WORDCLOUD_EMPTY, true);
                }
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.lockObject = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleLockActiveObject();
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.lockLayerObject = function (object){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleLockObject(object);
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.flipObject = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleFlipX();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.saveObject = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.saveCanvasObject();

                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };


        $scope.saveObjectAllFormat = function () {

            if($scope.fabric.checkBackgroundImage()){

                $scope.beforeSave();
                var objects_svg = $scope.fabric.designedSVGObjects;
            
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'svg',
                        object: JSON.stringify(objects_svg)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });

                $scope.beforeSave();
                var objects_png = $scope.fabric.designedPNGObjects;
            
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'png',
                        object: JSON.stringify(objects_png)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });

                $scope.beforeSave();
                var objects_jpg = $scope.fabric.designedJPGObjects;
            
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'jpg',
                        object: JSON.stringify(objects_jpg)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });


                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };
        
        
        $scope.reloadDesign = function (data, indexKey) {

            $('.canvas-loader').fadeIn();
            $('.canvas-container').addClass('blur');

            var content =  JSON.parse( data.reload.post_content );

            if( content ) {
                /* WK */
                var obj = content.object;
                $scope.fabric.loadJSON( obj[0] );     

                $scope.defaultPrice = 123;
                $scope.orignalPrice = 123;
                $scope.defaultCurrency = 'USD';
                $scope.defaultProductTitle = data.defaultProductTitle;
                _this.initProductSubImages(content.product_id, content.color_index);

                $scope.product_id = content.product_id;
                $scope.colorIndex = content.color_index;
                $scope.reloadData = data;  

                setTimeout(function(){
                    var f = content.start_design.inputs.front_color;
                    var b = content.start_design.inputs.back_color;
                    $('.t-0').html(f).attr('data-count', f);
                    $('.t-1').html(b).attr('data-count', b);
                }, 2500);    

                $('[name="add_name"]').prop( "checked", JSON.parse(content.add_name.status ) );
                $('[name="add_number"]').prop( "checked", JSON.parse(content.add_number.status ) );


                $('.text-add-name').text(content.add_name.title);
                $('.font-add-name').find('.object-font-family-preview').text(content.add_name.font).css({'font-family':content.add_name.font});
                $('.btn-add-name').css({'background-color': content.add_name.hex }).attr('data-color', content.add_name.hex);

                $('.text-add-number').text(content.add_number.title);
                $('.font-add-number').find('.object-font-family-preview').text(content.add_number.font).css({'font-family':content.add_number.font});
                $('.btn-add-number').css({'background-color': content.add_number.hex }).attr('data-color', content.add_number.hex);

                if( $('.toggle-team:checked').length ) {
                    $('.name-number-data').show();
                } else {
                    $('.name-number-data').hide();        
                }

                $('.mt-repeater-item').remove();

                // populate name & number
                $.each(content.team, function(k, v){
                    if( v ) {
                        $('.mt-repeater-add').trigger('click'); 
                        $('[name="team['+k+'][name]"]').val(v.name);
                        $('[name="team['+k+'][number]"]').val(v.number);  
                    }
                });

                // Append sizes
                $.each(JSON.parse($scope.sizes), function(k, v){
                    $('.team-sizes').append($('<option>', { 
                        value: v,
                        text : v 
                    }));
                });

                // select size
                $.each(content.team, function(k, v){
                    if( v ) {
                        $('[name="team['+k+'][size]"]').val(v.size).trigger('change');  
                    }
                });


                $scope.isloaded = true;
                $scope.$broadcast('rebuild:me');

                reload = true;
                
                setTimeout(function(){ 
                    $('.canvas-loader').fadeOut('fast');
                    $('.canvas-container').removeClass('blur');
                }, 1000);

            }
        };

        $scope.saveDesign = function (action) {


            if($scope.fabric.checkBackgroundImage()){
                
                $('.publish-loader').show();
                $('#get-quote .btn').addClass('disabled');

                var start = 0;
                
                if( $('#design_title').val() && $('#email').val()  ) {
                    // Validate each canvas
                    var subs = $('.canvas_sub_image li:visible img').map(function() {
                        return [$(this).data('index')];
                    }).get();
                    subs.push(0);
                    $.each(subs, function(k, v){ 
                        setTimeout(function() {
                            $('#subimg-'+v).trigger('click');
                        }, 500*k);
                    });

                    var start = 500*($('.canvas_sub_image li:visible img').length+1);
                }

                setTimeout(function() {

                    $scope.beforeSave();
                    var objects = $scope.fabric.designedObjects,
                        png = $scope.fabric.designedPNGObjects,                                
                        svg = $scope.fabric.designedSVGObjects,   
                        reload = _this.getSearchParams('reload');

                    $http({
                        method: 'post',
                        url:$scope.REQUEST_URL.SAVE_DESIGN,
                        data: {
                            object: JSON.stringify(objects),
                            png: JSON.stringify(png),
                            svg: JSON.stringify(svg),
                            product_id: $scope.product_id,
                            colorIndex: $scope.colorIndex,
                            comment: $('#comment').val(),
                            design_title: $('#design_title').val(),
                            email: $('#email').val(),
                            team: $('#form-team').serialize(),
                            add_name: JSON.stringify({ 
                                status: $('[name="add_name"]').is(':checked'),
                                hex: $('.btn-add-name').attr('data-color'),
                                title: $('.text-add-name').text(),
                                font: $('.font-add-name .object-font-family-preview').text()
                            }), 
                            add_number: JSON.stringify({
                                status: $('[name="add_number"]').is(':checked'),
                                hex: $('.btn-add-number').attr('data-color'),
                                title: $('.text-add-number').text(),
                                font: $('.font-add-number .object-font-family-preview').text()
                            }), 
                            reload : reload,   
                            action : action         

                        },
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        transformRequest: _this.transformRequest
                    }).success(function (data, status, headers, config) {

                        if( action == 'pre-save' ) {
                            $('.error-msg').html('');
                            if( data.error ) {
                                $('.cal-form').hide(); 
                                $('.customer-details').show(); 

                                $.each(data.msg, function(k, v) {
                                    $('.error-'+k).html(v);
                                });
                            } else {
                                $('.cal-form').show(); 
                                $('.customer-details').hide();                        
                                history.pushState(null, null, '?reload='+data.token);
                            }
                            
                            $('.publish-loader').hide();
                            $('#get-quote .btn').removeClass('disabled');
                            return false; 
                        } 

                        history.pushState(null, null, '?reload='+data.token);

                        if(data.status){
                            $mdDialog.show(
                                $mdDialog.alert()
                                    .parent(angular.element(document.querySelector('#popupContainer')))
                                    .clickOutsideToClose(true)
                                    .title('Design Saved')
                                    .textContent(data.message)
                                    .ariaLabel('Success')
                                    .ok('Got it!')
                            );

                            if( action == 'add' ) {
                                $.get($scope.REQUEST_URL.MINI_CART, function(data) {
                                    $('.mini-cart .dropdown-menu').html(data);
                                    $('.mini-cart .dropdown-menu').slideDown();
                                    setTimeout(function(){
                                        $('.mini-cart .dropdown-menu').slideUp();
                                    }, 5000);           
                                    $('.cart-total').html($('.mini-cart-total').data('count'));
                                });
                            }
                
                            $('#get-quote').modal('hide');

                        }
                    }).error(function (data, status, headers, config) {
                        $scope.$broadcast("AjaxCallHappened",false);
                    });

                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();

                }, start);

            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }


        };

        $scope.saveObjectAsSvg = function () {

            if($scope.fabric.checkBackgroundImage()){
                
                $scope.beforeSave();
                var objects = $scope.fabric.designedSVGObjects;
            
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'svg',
                        object: JSON.stringify(objects)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    if(data.status){
                        $mdDialog.show(
                            $mdDialog.alert()
                                .parent(angular.element(document.querySelector('#popupContainer')))
                                .clickOutsideToClose(true)
                                .title('Design Saved')
                                .textContent('Design has been saved. You can find them into "saved_design" directory.')
                                .ariaLabel('Success')
                                .ok('Got it!')
                        );
                    }
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });

                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.saveObjectAsPng = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.beforeSave();
                var objects = $scope.fabric.designedPNGObjects;
                                
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'png',
                        object: JSON.stringify(objects)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    if(data.status){
                        $mdDialog.show(
                            $mdDialog.alert()
                                .parent(angular.element(document.querySelector('#popupContainer')))
                                .clickOutsideToClose(true)
                                .title('Design Saved')
                                .textContent('Design has been saved. You can find them into "saved_design" directory.')
                                .ariaLabel('Success')
                                .ok('Got it!')
                        );
                    }
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });

                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.saveObjectAsJpg = function () {

            if($scope.fabric.checkBackgroundImage()){
                
                $scope.beforeSave();
                var objects = $scope.fabric.designedJPGObjects;
            
                $http({
                    method: 'post',
                    url:$scope.REQUEST_URL.SAVE_DESIGN,
                    data: {
                        type: 'jpg',
                        object: JSON.stringify(objects)
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: _this.transformRequest
                }).success(function (data, status, headers, config) {
                    if(data.status){
                        $mdDialog.show(
                            $mdDialog.alert()
                                .parent(angular.element(document.querySelector('#popupContainer')))
                                .clickOutsideToClose(true)
                                .title('Design Saved')
                                .textContent('Design has been saved. You can find them into "saved_design" directory.')
                                .ariaLabel('Success')
                                .ok('Got it!')
                        );
                    }
                }).error(function (data, status, headers, config) {
                    $scope.$broadcast("AjaxCallHappened",false);
                });

                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.downloadObject = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.downloadCanvasObject();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }

        };

        $scope.downloadObjectAsPdf = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.downloadCanvasObjectAsPDF();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.printObject = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.printCanvasObject();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.undo = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.undo();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.redo = function () {
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.redo();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.zoomObject = function (action) {

            if($scope.fabric.checkBackgroundImage()){
                if(action == 'zoomin') {
                    $scope.fabric.zoomInObject();
                }else if(action == 'zoomout'){
                    $scope.fabric.zoomOutObject();
                }else{
                    $scope.fabric.resetZoom();
                }
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.curveText = function(){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleText();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.toggleBold = function(){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleBold();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.toggleItalic = function(){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleItalic();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.toggleUnderline = function(){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleUnderline();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.toggleLinethrough = function(){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.toggleLinethrough();
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.toggleFont = function (font){
            if($scope.fabric.checkBackgroundImage()){
                $scope.fabric.selectedObject.fontFamily = font;
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
            }
        };

        $scope.isLocked = function(){
            if($scope.fabric.checkBackgroundImage()){
                return $scope.fabric.isLocked();
            }else{
                return false;
            }
        };

        $scope.isObjectLocked = function(object){
            if($scope.fabric.checkBackgroundImage()){
                return $scope.fabric.isObjectLocked(object);
            }else{
                return false;
            }
        };

        $scope.chooseColors = function () {
    
            var colors = $('#screen-colors li span.selected').map(function() {
                return [$(this).data('color')];
            }).get();
            $scope.fabric.setColors(colors);
            $scope.fabric.countObjectColors();
        };

        $scope.choosePrintColors = function () {
    
            var colors = $('#print-color-added span').map(function() {
                return [$(this).data('color')];
            }).get();
            $scope.fabric.setColors(colors);
            $scope.fabric.countObjectColors();
        };

        $scope.$on('AjaxCallHappened', function (event, data) {
            if (data.status == true) {
                _this.showNotification(data.message, false);
            }  else {
                _this.showNotification($scope.NOTIFICATION_MESSAGES.GENERAL_ERROR, false);
            }
        });
        $scope.addImageUpload = function (data) {
            var obj = angular.fromJson(data);
            $scope.addImage(obj.filename);
        };
        $scope.selectCanvas = function () {
            $scope.canvasCopy = {
                width: $scope.fabric.canvasOriginalWidth,
                height: $scope.fabric.canvasOriginalHeight
            };
        };
        $scope.setCanvasSize = function () {
            $scope.fabric.setCanvasSize($scope.canvasCopy.width, $scope.canvasCopy.height);
            $scope.fabric.setDirty(true);
            delete $scope.canvasCopy;
        };

        $scope.fillColor  = function (value) {
            $scope.fabric.selectedObject.fill = value;
        };

        $scope.fillStokeColor  = function (value) {
            $scope.fabric.selectedObject.stroke = value;
            $scope.fabric.setFillStroke(value);
        };

        $scope.fillstrokeWidth  = function (value) {
            $scope.fabric.selectedObject.strokeWidth = value;
            $scope.fabric.setFillStrokeWidth(value);
        };

        $scope.setSrc  = function (src) {
            $scope.fabric.selectedObject.src = src;
            $scope.fabric.setSrc(src);
        };

        $scope.fillTint = function (value){
            $scope.fabric.selectedObject.tint = value;
            $scope.fabric.applyTint();
        };

        $scope.resetTint = function (){
            $scope.fabric.resetTint();
        };

        $scope.toggleTint = function (value){
            $scope.fabric.selectedObject.tint = value;
            $scope.fabric.toggleTint();
        };

        $scope.toggleReverse = function (value){
            $scope.fabric.toggleReverse(value);
        };

        $scope.radius = function (value) {
            $scope.fabric.radius(value);
        };

        $scope.spacing = function (value) {
            $scope.fabric.spacing(value);
        };

        $scope.opacity = function (value) {
            $scope.fabric.selectedObject.opacity = value;
        };

        $scope.deleteObject = function (object){

            var confirm = $mdDialog.confirm()
                .title('')
                .content('Are you sure you want to remove selected object?')
                .ariaLabel('Confirm')
                .ok('Ok')
                .cancel('Cancel');
            $mdDialog.show(confirm).then(function() {
                $scope.fabric.deleteObject(object);
                $scope.fabric.setDirty(true);
                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
                $scope.$broadcast('rebuild:layer');
            });
        };

        $scope.toastPosition = angular.extend({},last);
        $scope.getToastPosition = function() {
            _this.sanitizePosition();
            return Object.keys($scope.toastPosition)
                .filter(function(pos) { return $scope.toastPosition[pos]; })
                .join(' ');
        };

        $scope.next = function() {
            $scope.data.selectedIndex = Math.min($scope.data.selectedIndex + 1, 2) ;
        };
        $scope.previous = function() {
            $scope.data.selectedIndex = Math.max($scope.data.selectedIndex - 1, 0);
        };

        $scope.updatePage = function(){
/*            $mdDialog.show(
                $mdDialog.alert()
                    .parent(angular.element(document.querySelector('#popupContainer')))
                    .clickOutsideToClose(true)
                    .title('Ohh!')
                    .content('You should buy this product, visit "http://designtailor.veepixel.com/"')
                    .ariaLabel('Alert Dialog')
                    .ok('Got it!')
            );*/
        };

        $scope.setImageFilter = function (checked, value){
           (checked == true) ? checked = false: checked = true;
            $scope.fabric.applyImageFilter(checked, value);
        };

        $scope.increment = function(item){
           item.count += 1;
        };

        $scope.counter = 1; 
        $scope.increments = function() {
            var countVal = $scope.counter; 
                $scope.counter++; 
            $scope.updateQuantity($scope.counter);
        };
        $scope.decrement = function() {
            var countVal = $scope.counter;
            if(countVal > 1){
                $scope.counter--;
            }
            $scope.updateQuantity($scope.counter);
        };

        $scope.updateQuantity = function (quantity) {
            if(!isNaN(quantity)){
                if(quantity <= 0){
                    $scope.quantity = 1;
                    $scope.defaultPrice = $scope.orignalPrice;
                }else{
                    $scope.defaultPrice = $scope.orignalPrice * quantity;
                }
            }else{
                $scope.quantity = 1;
                $scope.defaultPrice = $scope.orignalPrice;
                _this.showNotification($scope.NOTIFICATION_MESSAGES.TYPE_NUMBER_REQUIRED, true);
            }

        };

        $scope.addToCart = function () {

            if($scope.fabric.checkBackgroundImage()){
                $scope.saveObjectAllFormat();
                $mdDialog.show(
                    $mdDialog.alert()
                        .parent(angular.element(document.querySelector('#popupContainer')))
                        .clickOutsideToClose(true)
                        .title('Thank You')
                        .textContent('Product has been saved and added to cart.')
                        .ariaLabel('Success')
                        .ok('Got it!')
                );
            }else{
                _this.showNotification($scope.NOTIFICATION_MESSAGES.CANVAS_EMPTY, true);
            }
        };

        $scope.beforeSave = function () {
                $scope.fabric.designedObjects[$scope.activeDesignObject]  = $scope.fabric.exportCanvasObjectAsJson();
                $scope.fabric.designedSVGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsSvg();
                $scope.fabric.designedPNGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsPng();
                $scope.fabric.designedJPGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsJpg();
        };

        $scope.prodctByCat = function (val){  
            $scope.productCategory = val;
            _this.initProducts();
        };

        $scope.hasCategory = function (product){
            var category = $scope.productCategory;
            var categories = JSON.parse(product.category);
            return (category == "all" || $.inArray(category, categories));
        }; 


        $scope.loadProduct = function (title, image, id, price, currency, indexKey, attr, colorIndex) {
            indexKey = (typeof indexKey == "undefined")? null:indexKey;
            $scope.counter = 1; 
        
            if(indexKey != null) {


                $scope.fabric.designedObjects[$scope.activeDesignObject]  = $scope.fabric.exportCanvasObjectAsJson();
                $scope.fabric.designedSVGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsSvg();
                $scope.fabric.designedPNGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsPng();
                $scope.fabric.designedJPGObjects[$scope.activeDesignObject] = $scope.fabric.saveCanvasObjectAsJpg();


                if($scope.fabric.designedObjects[indexKey] != null){
            
                    var attr = JSON.parse(attr);
                    $scope.fabric.designedObjects[indexKey].objects[0]['width']  = parseFloat(attr.width.replace('px', ''));
                    $scope.fabric.designedObjects[indexKey].objects[0]['height'] = parseFloat(attr.height.replace('px', ''));
                    $scope.fabric.designedObjects[indexKey].objects[0]['left']   = parseFloat(attr.left.replace('px', ''));
                    $scope.fabric.designedObjects[indexKey].objects[0]['top']    = parseFloat(attr.top.replace('px', ''));
                    $scope.fabric.designedObjects[indexKey].objects[0]['stroke'] = attr.borderColor ? attr.borderColor : '#fff';

                    $scope.fabric.designedObjects[indexKey].backgroundImage['src'] = image;

          
                    $scope.fabric.loadJSON($scope.fabric.designedObjects[indexKey]);
                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();            
                
                } else {
     
          
                    $scope.clearCanvas();
                    // $scope.fabric.clearBg();

                    $scope.fabric.addCanvasBackground(image);
                    $scope.fabric.addDesignArea(attr);
                    if( reload ) {
                        
                        var content = JSON.parse( $scope.reloadData.reload.post_content  );
                        var obj = content.object;

                        $scope.fabric.loadJSON( obj[indexKey] );    

                        $scope.product_id = content.product_id;
                        $scope.colorIndex = content.color_index;
                    }              

                    $scope.objectLayers = [];
                    $scope.objectLayers = $scope.fabric.canvasLayers();
                }
                $scope.activeDesignObject = indexKey;

            }
          
            if(indexKey == null){

                 // $scope.clearCanvas();
                // $scope.fabric.clearBg();

               // Validate each canvas
                var subs = $('.canvas_sub_image li:visible img').map(function() {
                    return [$(this).data('index')];
                }).get();
                
                subs.push(0);
                $.each(subs, function(k, v){ 
                    setTimeout(function() {
                        $('#subimg-'+v).trigger('click');
                    }, 500*k);
                });

                var start = 500*($('.canvas_sub_image li:visible img').length+1);

                setTimeout(function(){ 

                    $scope.colorIndex = colorIndex;
                    $scope.defaultPrice = price;
                    $scope.orignalPrice = price;
                    $scope.defaultCurrency = currency;
                    $scope.defaultProductTitle = title;
                    
                    $scope.fabric.addCanvasBackground(image);

                    setTimeout(function(){ 
                        $scope.fabric.addCanvasBackground(image);
                        $('.canvas-loader').fadeOut('fast');
                        $('.canvas-container').removeClass('blur');
                        $scope.fabric.addDesignArea(attr);
                    }, 1600);
                    
                    reload = false;

                    _this.initProductSubImages(id, colorIndex);
                
                }, start);

                $('body').attr('data-product-id', id);
                $('body').attr('data-color-index', colorIndex);
            }
   
            $scope.product_id = id;




            if(indexKey != null) {
                $('body').attr('data-index', indexKey);
            }
            // $scope.fabric.countObjectColors();
            $scope.isloaded = true;
            $scope.$broadcast('rebuild:me');
        };


        this.initProductSubImages = function (id, index) {

            $http({
                method: 'get',
                url: $scope.REQUEST_URL.LOAD_PRODUCT_SUB_IMAGES+'/'+id+'?i='+index,
                dataType : 'json',
                headers: {'Content-Type': 'application/json'}
            }).success(function (data, status, headers, config) {
                $scope.defaultProductId = id;

                $scope.productImages = data.images;
               // $scope.fabric.designedObjects = {};
/*                $.each($scope.productImages, function(index,value){
                    $scope.fabric.designedObjects[index] = null;
                    $scope.fabric.designedSVGObjects[index] = null;
                    $scope.fabric.designedPNGObjects[index] = null;
                    $scope.fabric.designedPNGObjects[index] = null;

                });*/

                $scope.objectLayers = [];
                $scope.objectLayers = $scope.fabric.canvasLayers();
                $scope.fabric.countObjectColors();
                $scope.isloaded = false;
            }).error(function (data, status, headers, config) {
                $scope.isloaded = false;
            });
        };

        $scope.shareOnFacebook = function (e){
            e.preventDefault();
            FB.ui(
                {
                    method: 'feed',
                    name: 'Classic blue tees',
                    link: 'http://demo.veesys.com/tshirt/',
                    picture: 'http://demo.veesys.com/tshirt/images/export.png',
                    caption: 'Classic blue tees',
                    description: 'This classic blue will upgrade your casual collection. Made from cotton, this half sleeves tee ensures great comfort all day long. Style it with straight fit denims and sneakers for that smart look.',
                    message: ''
                }
            );
        };

        $scope.shareOnTwitter = function (e){
            e.preventDefault();
            window.open("https://twitter.com/share?url=" + escape(window.location.href) + "&text=" + document.title, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
            return false;
        };

        $scope.graphics_load_more = function (page){

            var str = $scope.graphicsCategory;
            var lowerCase = str.toLowerCase();
            var category = lowerCase.replace(" ", "_");

            $http({
                method: 'get',
                url: $scope.REQUEST_URL.LOAD_GRAPHICS+category+'_'+$scope.graphicsPage+'.json',
                dataType : 'json',
                headers: {'Content-Type': 'application/json'}
            }).success(function (data, status, headers, config) {
                if(data.loadMore){
                    $scope.loadMore = true;
                }else{
                    $scope.loadMore = false;
                }
                $scope.graphicsPage = 1+data.page;
                var finalObj = $scope.graphics.concat(data.data_result);
                $scope.graphics = finalObj;
                $scope.$broadcast("AjaxCallHappened",data);
                $scope.$broadcast('rebuild:me');
            }).error(function (data, status, headers, config) {
                $scope.$broadcast("AjaxCallHappened",false);
            });
        };

        $scope.ColorSelector = function (){
            $("#selector_icon").on('click', function(event) {
                $("body").toggleClass('body_overlay');
                $(this).parent(".customizer").toggleClass('customizer_toggle');
                return false;
            });
        };

        $scope.CanvasBgSelector = function (){
            $("#canvas_color_selector > li").on('click', function(event) {
                $('.canvas_section').attr('id', $(this).attr('class'));
                $(".canvas_image").css("background-image","url("+$(this).data("attr")+")");
                $(".customizer").removeClass('customizer_toggle');
                $("body").removeClass('body_overlay');
                return false;
            });
        };
        $scope.CanvasPosition = function (){
            $("#canvas-pos > label").on('click', function(event) {
                var canvas_id = $(this).find("input").attr("id");
                if(canvas_id == "pos_left"){
                    $(".editor_section").addClass("pull-right");
                }else{
                    $(".editor_section").removeClass("pull-right");
                }
            });
        };

        this.transformRequest = function(obj) {
            var str = [];
            for(var p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            return str.join("&");
        };

        this.sanitizePosition = function() {
            var current = $scope.toastPosition;
            if ( current.bottom && last.top ) current.top = false;
            if ( current.top && last.bottom ) current.bottom = false;
            if ( current.right && last.left ) current.left = false;
            if ( current.left && last.right ) current.right = false;
            last = angular.extend({},current);
        };

        this.showNotification = function (message, scroll) {

/*            $mdToast.show(
                $mdToast.simple()
                    .content(message)
                    .position($scope.getToastPosition())
                    .hideDelay(3000)
            );
            if(scroll) {
                $('html, body').animate({scrollTop: $(document).height()}, 1500);
            }*/
        };

        this.initProducts = function () {
            $scope.productCategory = $scope.productCategory ? $scope.productCategory : 'all';

            $http({
                method: 'get',
                url: $scope.REQUEST_URL.LOAD_PRODUCTS+'/'+$scope.productCategory,
                dataType : 'json',
                headers: {'Content-Type': 'application/json'}
            }).success(function (data, status, headers, config) {

                $scope.products = data.products;
                $scope.isloaded = false;
                $scope.$broadcast("AjaxCallHappened",data);
            }).error(function (data, status, headers, config) {
                $scope.isloaded = false;
                $scope.$broadcast("AjaxCallHappened",false);
            });
        };

        $scope.loadByGraphicsCat = function (val){
            $scope.graphicsCategoryId = val;
            $scope.graphicsPage = 1;
            _this.initGraphics();
            $scope.graphic_icons = true;
        };

        $scope.ShowGraphicIcons = function (){
            $scope.graphic_icons = false;
        };

        $scope.loadByGraphicsCategory = function (){
            $scope.graphicsPage = 1;
            _this.initGraphics(); 
        };

        this.initGraphics = function () {

            var id = $scope.graphicsCategoryId;

            $http({
                method: 'get',
                url: $scope.REQUEST_URL.LOAD_GRAPHICS+'/'+id,
                dataType : 'json',
                headers: {'Content-Type': 'application/json'}
            }).success(function (data, status, headers, config) {

                if(data.loadMore){
                    $scope.loadMore = true;
                }else{
                    $scope.loadMore = false;
                }
                $scope.graphicsPage = 1+data.page;
                $scope.graphics = data.data_result;
                $scope.isloaded = false;
                $scope.$broadcast("AjaxCallHappened",data);
                $scope.$broadcast('rebuild:me');
            }).error(function (data, status, headers, config) {
                $scope.isloaded = false;
                $scope.$broadcast("AjaxCallHappened",false);
            });
        };

        $scope.loadByProductName = function (){

            if($scope.productByName == 'ASC') {

                $scope.predicate = 'name';
                $scope.reverse = false;

            }else{

                $scope.predicate = 'name';
                $scope.reverse = true;

            }

        };

        this.getSearchParams = function (k){
            var p={};
            location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
            return k?p[k]:p;
        };

        this.initSettings = function () {

            var reload = _this.getSearchParams('reload');

            $http({
                method: 'get',
                url: SETTINGS_INIT_URL,
                params: { 'reload' : reload },
                dataType : 'json',
                headers: {'Content-Type': 'application/json'}
            }).success(function (data, status, headers, config) {
                
                $scope.NOTIFICATION_MESSAGES = data.settings.notification_messages;
                $scope.REQUEST_URL = data.settings.request_url;


                $scope.fb_app_id = data.settings.social_settings.fb_app_id;

                $scope.graphicsPage = data.settings.general_settings.graphicsPage;
                $scope.defaultProductId = data.settings.general_settings.defaultProductId;
                $scope.defaultProductImage = data.settings.general_settings.defaultProductImage;
                $scope.quantity = data.settings.general_settings.quantity;
                $scope.defaultPrice = data.settings.general_settings.defaultPrice;
                $scope.orignalPrice = $scope.defaultPrice;
                $scope.defaultCurrency = data.settings.general_settings.defaultCurrency;
                $scope.defaultProductTitle = data.settings.general_settings.defaultProductTitle;
                $scope.qrCode = data.settings.general_settings.qrCode;
                $scope.enter_drawing_mode = data.settings.general_settings.enter_drawing_mode;
                $scope.drawing_mode_selector = data.settings.general_settings.drawing_mode_selector;
                $scope.drawing_line_width = data.settings.general_settings.drawing_line_width;
                $scope.drawing_color = data.settings.general_settings.drawing_color;
                $scope.drawing_line_shadow = data.settings.general_settings.drawing_line_shadow;
                $scope.primaryColor = data.settings.general_settings.primaryColor;
                $scope.secondaryColor = data.settings.general_settings.secondaryColor;
                $scope.graphicsCategories = data.settings.general_settings.graphicsCategories;
                $scope.productCategories = data.settings.general_settings.productCategories;
                $scope.productByNames = data.settings.general_settings.productByNames;
                $scope.graphicsCategory = data.settings.general_settings.graphicsCategory;
                $scope.productByName = data.settings.general_settings.productByName;
                $scope.predicate = data.settings.general_settings.predicate;
                $scope.reverse = data.settings.general_settings.reverse;
                $scope.loadMore = data.settings.general_settings.loadMore;

                $scope.sizes = data.settings.general_settings.sizes;
                $scope.fonts = data.settings.general_settings.fonts;
                $scope.attr = data.settings.general_settings.defaultProductAttr;

                $scope.fabric.setTextDefaults(data.settings.general_settings.textDefaults);

                $scope.productCategory = data.settings.general_settings.productCategory;
                $scope.reload = data.settings.general_settings.reload;
                $scope.colorIndex = data.settings.general_settings.colorIndex;

                $scope.changeColorScheme();
                _this.initProducts(); 

                $('body').attr('data-sizes', $scope.sizes);

                if( $scope.reload ) {
                    $scope.reloadDesign( data.settings.general_settings );
                    $('#comment').val( data.settings.general_settings.detail.comment );
                    $('#email').val( data.settings.general_settings.detail.email );
                    $('#design_title').val( data.settings.general_settings.detail.design_title );
                } else {                

                    $scope.loadProduct($scope.defaultProductTitle, $scope.defaultProductImage,$scope.defaultProductId, $scope.defaultPrice, $scope.defaultCurrency, null, $scope.attr, $scope.colorIndex);            
                }

                $('body').attr('data-product-id', $scope.product_id)
                         .attr('data-color-index', 1)
                         .attr('data-index', 0);

                _this.initGraphics();
                $scope.fabric.readyHandTool($scope.drawing_mode_selector, $scope.drawing_color, $scope.drawing_line_width, $scope.drawing_line_shadow);
                

            }).error(function (data, status, headers, config) {
                $scope.isloaded = false;
            });

        };

        $scope.initFBUi = function (){
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : $scope.fb_app_id,
                    xfbml      : true,
                    version    : 'v2.5'
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        };

        $scope.init = function () {

                _this.initSettings();

                jQuery(document).on("change", ".upload", function (){
                    jQuery("#uploadFile").val($(this).val());
                });

                $scope.ColorSelector();

                $scope.CanvasBgSelector();

                $scope.CanvasPosition();

                $scope.initFBUi();

            jQuery(window).load(function(){
                 jQuery(".editor_section").height(jQuery(".canvas_section").height()); 
                 jQuery("#Products .thumb_listing ul > li:first-child a").trigger("click");
            });  
            $scope.fabric = new Fabric({
                JSONExportProperties: FabricConstants.JSONExportProperties,
                textDefaults: FabricConstants.textDefaults,
                shapeDefaults: FabricConstants.shapeDefaults,
                curvedTextDefaults: FabricConstants.curvedTextDefaults,
                imageDefaults: FabricConstants.imageDefaults,
                imageFilters: FabricConstants.imageFilters,
                json: {}
            });
            $scope.isloaded = false;
        };
        $scope.$on('canvas:created', $scope.init);
        Keypress.onSave(function () {
            $scope.updatePage();
        });
    }
]);

