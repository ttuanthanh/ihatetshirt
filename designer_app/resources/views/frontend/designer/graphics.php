   <div class="tab-pane clearfix" id="Graphics">
            <div class="graphic_options clearfix">
                <ul>

                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6 active">
                        <div>
                            <a class="" href="#text" role="tab" data-toggle="tab" ng-click="deactivateAll()">
                            <span>TEXT</span>
                            </a>
                        </div>
                    </li>

                    <?php if( App\Setting::get_setting('designer_upload') ): ?>
                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <div>
                            <a class="" href="#upload_own" aria-controls="upload_own" role="tab" data-toggle="tab" ng-click="exitDrawing()">
                            <span>UPLOAD</span>
                            </a>
                        </div>
                    </li>
                    <?php endif ?>

                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <div>
                            <a class="" href="#clip_arts" aria-controls="clip_arts" role="tab" data-toggle="tab" ng-click="exitDrawing()">
                            <span>CLIPART</span>
                            </a>
                        </div>
                    </li>

                    <?php if( App\Setting::get_setting('designer_qrcode') ): ?>
                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <div>
                            <a class="" href="#qr_code" aria-controls="qr_code" role="tab" data-toggle="tab" ng-click="exitDrawing()">
                            <i class="fa fa-qrcode"></i>
                            <span>Qr code</span>
                            </a>
                        </div>
                    </li>
                    <?php endif ?>

                    <?php if( App\Setting::get_setting('designer_hand_draw') ): ?>
                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <div>
                            <a class="" href="#hand_draw" aria-controls="hand_draw" role="tab" data-toggle="tab" ng-click="enterDrawing();">
                            <i class="fa fa-pencil-square-o"></i>
                            <span>Hand draw</span>
                            </a>
                        </div>
                    </li>
                    <?php endif ?>
                    
                </ul>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade" id="clip_arts">
                    <div class="form-content graphic_types clearfix" ng-show="!graphic_icons">
    
                        <input type="text" id="find-graphic-types" class="form-control margin-bottom-20" placeholder="Search for ..." onkeyup="findColors()">

                        <div ng-repeat="(graphicsCategoryId, graphicsCategory) in graphicsCategories" value="{{graphicsCategory}}"  ng-click="loadByGraphicsCat(graphicsCategoryId)" ng-model="graphicsCategory">
                            <div class="{{graphicsCategory.split(' ').join('') | lowercase}} hide"></div>
                            <span>    
                            {{graphicsCategory}}
                            </span>
                        </div>
                    </div>
                    <span ng-show="graphic_icons" class="back_to_graphic btn btn-default" ng-click="ShowGraphicIcons()">
                    <i class="fa fa-angle-left"></i> Back
                    </span>
                    <div class="graphic_icons" ng-show="graphic_icons">
                        <div class="cal-lg-12 filter_by_cat">
                            <md-input-container style="">
                                <label>Sort by category</label>
                                <md-select ng-model="graphicsCategoryId" ng-change="loadByGraphicsCategory();">
                                    <md-option ng-repeat="(graphicsCategoryId, graphicsCategory) in graphicsCategories" value="{{graphicsCategoryId}}">{{graphicsCategory}}</md-option>
                                </md-select>
                            </md-input-container>
                        </div>
                        <div class="col-lg-12 thumb_listing scrollme" rebuild-on="rebuild:me" ng-scrollbar is-bar-shown="barShown" ng-class="fabric.selectedObject ? 'activeControls' : ''">
                            <ul>
                                <li ng-repeat="graphic in graphics"><a href="javascript:void(0);" ng-click='addShape(graphic)'><img data-ng-src="{{graphic}}" alt="" width="120px;"></a></li>
                            </ul>
                            <a ng-if="loadMore" class="loadMore" ng-click="graphics_load_more(graphicsPage)">Load More</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="upload_own">
                    <div class="col-lg-12 thumb_listing">
                        <div class="well" >
                            <form method="post" id="upload-image" enctype="multipart/form-data">
                                <input type="hidden" name="bg" value="0">
                                <div class="fileUpload btn btn-primary">
                                    <span>Choose File</span>
                                    <input type="file" ngf-select="onFileSelect('confirm');" 
                                    ng-model="picFile" name="file" accept="image/*, .pdf, .ai, .eps, .svg, .psd" ngf-max-size="2MB" class="upload">
                                </div>

                                <div class="pull-right">We accept the following file types:<br> <b>PNG, JPG, GIF, PDF, PSD, AI, EPS, SVG</b>
                                </div>

                                <p class="msg-graphic text-danger"></p>

                                <input id="uploadFile" placeholdFile NameName disabled="disabled" />
                                <span class="has-error" ng-show="myForm.file.$error.maxSize">File too large {{picFile.size / 1000000|number:1}}MB: max 2M</span>
                                <div class="clearfix"></div>
                                <span class="has-error" ng-show="myForm.file.$error.maxWidth">File width too large : Max Width 300px</span>
                                <div class="clearfix"></div>
                                <span class="has-error" ng-show="myForm.file.$error.maxHeight">File height too large : Max Height 300px</span>
                                <div class="clearfix"></div>
                                <span class="has-error" ng-show="uploadErrorMsg">{{uploadErrorMsg}}</span>
                        

                            </form>
                        </div>

                    <div class="graphic-list">
                        <?php $uploads = Session::get('upload_image'); 
                        if($uploads):
                        krsort($uploads);
                        ?>
                        <?php foreach($uploads as $upload): ?>
                            <div class="upload-thumb ng-scope">
                            <a href="javascript:void(0);" ng-click="addUploadImage('<?php echo asset($upload); ?>')"><img src="<?php echo asset($upload); ?>"></a>   
                            </div>            
                        <?php endforeach; 
                        endif;
                        ?>  
                    </div>
      
                    </div>
                </div>



                <!-- START TEXT -->
                <div role="tabpanel" class="tab-pane fade in active" id="text">
                    <div class="col-lg-12 thumb_listing">
                       
                        <div class="well" >
                                <h4>Add New Text</h4>

                            <div class="row form-group">
                                    <label>Enter text below</label>

                                <md-input-container flex>
                                    <label></label>
                                    <textarea  columns="1" id="textarea-text" style="text-align: {{ fabric.selectedObject.textAlign }}" ng-model="fabric.selectedObject.text"></textarea>
                                </md-input-container>
                                <div class="clearfix">
                                    <md-button class="md-raised md-cornered" ng-click="addText()" aria-label="Add Text"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Text</md-button>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>
                <!-- END TEXT -->

                <div role="tabpanel" class="tab-pane fade" id="qr_code">
                    <div class="col-lg-12 thumb_listing">
                        <div class="well" >
                            <div class="row form-group">
                                <md-input-container flex>
                                    <label>Enter website link or text here</label>
                                    <textarea  columns="1" id="textarea-text-qr-code" ng-model="fabric.selectedObject.textQRCode"></textarea>
                                </md-input-container>
                                <div class="clearfix">
                                    <md-button class="md-raised md-cornered" ng-click="addQRCode(fabric.selectedObject.textQRCode);" aria-label="Add QR Code"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add QR Code</md-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div role="tabpanel" class="tab-pane fade" id="hand_draw">
                    <div class="col-lg-12 thumb_listing">
                        <div class="well" >
                            <div class="row form-group">
                                <md-radio-group ng-model="drawing_mode_selector" ng-if="enter_drawing_mode == 'Cancel Drawing Mode'">
                                    <md-radio-button value="Pencil" class="md-primary" ng-click="changeDrawingMode('Pencil');">Pencil</md-radio-button>
                                    <md-radio-button value="Circle" class="md-primary" ng-click="changeDrawingMode('Circle');"> Circle </md-radio-button>
                                    <md-radio-button value="Spray" class="md-primary" ng-click="changeDrawingMode('Spray');">Spray</md-radio-button>
                                    <md-radio-button value="Pattern" class="md-primary" ng-click="changeDrawingMode('Pattern');">Pattern</md-radio-button>
                                    <md-radio-button value="hline" class="md-primary" ng-click="changeDrawingMode('hline');">H-line</md-radio-button>
                                    <md-radio-button value="vline" class="md-primary" ng-click="changeDrawingMode('vline');">V-line</md-radio-button>
                                    <md-radio-button value="square" class="md-primary" ng-click="changeDrawingMode('square');">Square</md-radio-button>
                                    <md-radio-button value="diamond" class="md-primary" ng-click="changeDrawingMode('diamond');">Diamond</md-radio-button>
                                </md-radio-group>
                            </div>
                            <br /><br />
                            <div class="col-sm-12 input-group colorPicker2" ng-if="enter_drawing_mode == 'Cancel Drawing Mode'">
                                <md-input-container flex>
                                    <label for="Line color">Line color:</label>
                                    <input type="text" value="" class="" colorpicker ng-model="drawing_color" ng-change="fillDrawing(drawing_color);"/>
                                </md-input-container>
                                <span class="input-group-addon" style="border: medium none #000000; background-color: {{drawing_color}}"><i></i></span>
                            </div>
                            <br />
                            <div class="row form-group handtool">
                                <md-input-container flex ng-if="enter_drawing_mode == 'Cancel Drawing Mode'">
                                    <label for="Line width">Line width:</label>
                                    <input class='col-sm-12' title="Line width" type='range' min="0" max="150" step=".01" ng-model="drawing_line_width" ng-change="changeDrawingWidth(drawing_line_width);"/>
                                </md-input-container>
                            </div>
                            <div class="row form-group handtool">
                                <md-input-container flex ng-if="enter_drawing_mode == 'Cancel Drawing Mode'">
                                    <label for="Line shadow">Line shadow:</label>
                                    <input class='col-sm-12' title="Line shadow" type='range' min="0" max="50" step=".01" ng-model="drawing_line_shadow" ng-change="changeDrawingShadow(drawing_line_shadow);"/>
                                </md-input-container>
                            </div>
                            <div class="row form-group">
                                <div class="clearfix">
                                    <center>
                                        <md-button class="md-raised md-cornered" ng-click="enterDrawingMode();" aria-label="{{enter_drawing_mode}}"><i class="fa fa-plus"></i>&nbsp;&nbsp;{{enter_drawing_mode}}</md-button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>