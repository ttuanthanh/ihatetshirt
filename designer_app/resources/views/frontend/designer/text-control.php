
<div class="" ng-class="fabric.selectedObject ? 'activeControlsElem' : ''" ng-if='fabric.selectedObject.type' ng-switch='fabric.selectedObject.type' ng-show="fabric.selectedObject.uid != 'add-name' && fabric.selectedObject.uid != 'add-number'">
    <div class="well">


    <div class="close-circle margin-bottom-20 btn btn-default"><b class="fa fa-angle-left" ng-click="deactivateAll();"><span>Back</span></b></div>
        <div class="clearfix"></div>
        <div class="row form-group" ng-show="fabric.selectedObject.type == 'text' || fabric.selectedObject.type == 'curvedText'">
            <label>Enter text below</label>
            <md-input-container flex>
                <label></label>
                <textarea  columns="1" id="textarea-text" style="text-align: {{ fabric.selectedObject.textAlign }}" ng-model="fabric.selectedObject.text"></textarea>
            </md-input-container>
        </div>
        <div class="row form-group" ng-show="fabric.selectedObject.type == 'text' || fabric.selectedObject.type == 'curvedText'" style="position: relative;">
            <md-button class="md-raised md-cornered dropdown-toggle" data-toggle="dropdown" aria-label="Font Family"><span class='object-font-family-preview' style='font-family: "{{ fabric.selectedObject.fontFamily }}";'> {{ fabric.selectedObject.fontFamily }} </span> <span class="caret"></span></md-button>
            <ul class="dropdown-menu">
                <li ng-repeat='font in fonts' ng-click='toggleFont(font.name);' style='font-family: "{{ font.name }}";'> <a>{{ font.name }}</a> </li>
            </ul>
        </div>

        <div class="row row-margin">
            <div class="row col-lg-6" title="Font Color">



                <div class="input-group colorPicker2 margin-bottom-20" ng-show="fabric.selectedObject.type != 'image' && fabric.selectedObject.type != 'path' && fabric.selectedObject.type != 'path-group'">

                    <md-input-container style="display:none;" flex>
                        <label for="Color">Color:</label>
                        <input type="text" value="" class="colorpick input-add-fill" colorpicker ng-model="fabric.selectedObject.fill" ng-change="fillColor(fabric.selectedObject.fill);"/>
                    </md-input-container>

                    <div class="list-color">
                        <label>Color</label><br>
                        <button class="dropdown-color btn btn-color btn-add-fill" style="background:{{fabric.selectedObject.fill}};" data-target="add-fill">
                            <i class="fa fa-caret-down"></i>
                        </button>
                        {{ fabric.selectedObject.fill }}
                    </div>
                </div>

            </div>
            <div class="row col-lg-6">

                <div class="input-group colorPicker2 margin-bottom-20" ng-show="fabric.selectedObject.type == 'text'">
                    <md-input-container style="display:none;" flex>
                        <label for="Color">Outline:</label>
                        <input type="text" value="" class="colorpick input-add-stroke" colorpicker ng-model="fabric.selectedObject.stroke" ng-change="fillStokeColor(fabric.selectedObject.stroke);"/>
                    </md-input-container>

                    <div class="list-color">
                        <label>Outline</label><br>
                        <button class="dropdown-color btn btn-color btn-add-stroke" style="background:{{fabric.selectedObject.stroke}};" data-target="add-stroke">
                            <i class="fa fa-caret-down"></i>
                        </button>
                        {{ fabric.selectedObject.stroke }}
                    </div>


                </div>

            </div>


            <div class="row col-lg-6" title="Reverse" ng-show="fabric.selectedObject.type == 'curvedText'">
                <md-checkbox ng-model="fabric.selectedObject.isReversed" aria-label="Reverse" ng-click="toggleReverse(fabric.selectedObject.isReversed);">Reverse </md-checkbox>
            </div>
        </div>

        <div class="row row-margin">

            <div class="row col-lg-6" title="Line height" ng-show="fabric.selectedObject.type == 'text'">
                <md-input-container flex>
                    <label><i class="fa fa-align-left"></i> (Line height)</label>
                    <input type='number' class="" ng-model="fabric.selectedObject.lineHeight" step=".1" />
                </md-input-container>
            </div>


            <div class="row col-lg-6" title="Line height" ng-show="fabric.selectedObject.type == 'text'">
                <md-input-container flex>
                    <label><i class="fa fa-align-left"></i> Outline Stroke</label>
                    <input type='number' class="" ng-model="fabric.selectedObject.strokeWidth" ng-change="fillstrokeWidth(fabric.selectedObject.strokeWidth);" step=".1"/>
                </md-input-container>
            </div>
            <div class="row col-lg-6" title="Reverse" ng-show="fabric.selectedObject.type == 'curvedText'">
                <md-checkbox ng-model="fabric.selectedObject.isReversed" aria-label="Reverse" ng-click="toggleReverse(fabric.selectedObject.isReversed);">Reverse </md-checkbox>
            </div>
        </div>

        <div class="row form-group transparency" ng-show="fabric.selectedObject.type == 'path-group'">

            <div class="row">
                <div class="col-md-4">
                    <img src="" class="img-thumbnail img-upload">
                </div>
                <div class="col-md-8">
                    <h4>Ink Colors :</h4>
                    <p class="text-muted">This helps us determine the pricing based on the number of colors in your design.</p>
                    <div class="list-colors"></div>
                </div>
            </div>


        </div>

        <div class="row form-group transparency" ng-show="fabric.selectedObject.type == 'image'">

            <div class="row">
                <div class="col-md-4">
                    <img src="" class="img-thumbnail img-upload">
                    <a href="" class="btn green btn-paint-tool margin-top-10 btn-block" data-id="{{ fabric.selectedObject.id }}"><i class="fa fa-paint-brush"></i> Paint Tool</a>
                </div>
                <div class="col-md-8">

                <h4>Ink Colors :</h4>
                <p class="text-muted">This helps us determine the pricing based on the number of colors in your design.</p>
                <div id="print-color-added" class="list-colors"></div>
                <hr>
                <a href="" class="btn blue btn-edit-colors">Edit Colors</a>
                    
                </div>
            </div>


        </div>

        <div class='row row-margin'>
            
            <div class="col-md-3">
                <h5>Align</h5>
                <md-button class="md-raised md-cornered" ng-click="horizontalAlign()"><i class='fa fa-arrows-h'></i>
                    <md-tooltip md-visible="horizontal.showTooltip" md-direction="right">Horizontal Align</md-tooltip>
                </md-button>
                <md-button class="md-raised md-cornered" ng-click="verticalAlign()"><i class='fa fa-arrows-v'></i>
                    <md-tooltip md-visible="vertical.showTooltip" md-direction="right">Vertical Align</md-tooltip>
                </md-button>                    
            </div>
            <div class="col-md-4">
                <h5>Transform</h5>
                <md-button class="md-raised md-cornered" ng-click="{ active: flipObject() }"><i class='fa fa-exchange'></i> 
                    <md-tooltip md-visible="flip.showTooltip" md-direction="right">Flip</md-tooltip>
                </md-button>

                <md-button class="md-raised md-cornered" ng-click="lockLayerObject(fabric.selectedObject);">
                    <i class="fa fa-unlock" ng-class="isObjectLocked(fabric.selectedObject) ? 'fa-lock' : 'fa-unlock'"></i>
                    <md-tooltip md-visible="flip.showTooltip" md-direction="right">{{ isObjectLocked(fabric.selectedObject) ? 'Unlock' : 'Lock' }}</md-tooltip>
                </md-button>


            </div>

            <div class="col-md-5"  ng-show="fabric.selectedObject.type == 'text' || fabric.selectedObject.type == 'curvedText'">

                <h5>Text Style</h5>
                <md-button class="md-raised md-cornered" ng-class="{ active: fabric.isBold() }" ng-click="toggleBold()" aria-label="Bold">
                    <i class='fa fa-bold'></i>
                    <md-tooltip md-direction="right">Bold</md-tooltip>
                </md-button>

                <md-button class="md-raised md-cornered" ng-class="{ active: fabric.isItalic() }" ng-click="toggleItalic()" aria-label="Italic">
                    <i class='fa fa-italic'></i>
                    <md-tooltip md-direction="right">Italic</md-tooltip>
                </md-button>

                <md-button class="md-raised md-cornered" ng-class="{ active: fabric.isUnderline() }" ng-click="toggleUnderline()" aria-label="Underline">
                    <i class='fa fa-underline'></i>
                    <md-tooltip md-direction="right">Underline</md-tooltip>
                </md-button>

                <md-button class="md-raised md-cornered" ng-class="{ active: fabric.isLinethrough() }" ng-click="toggleLinethrough()" aria-label="Strike through">
                    <i class='fa fa-strikethrough'></i>
                    <md-tooltip md-direction="right">Strikethrough</md-tooltip>
                </md-button>

            </div>

        </div>

        <div class="row form-group transparency" title="Radius" ng-show="fabric.selectedObject.type == 'curvedText'" style="margin-bottom: 0px;">
            <md-input-container flex>
                <label for="Radius">Radius:</label>
                <input class='col-sm-12' title="Radius" type='range' min="50" max="200" value="100" ng-model="fabric.selectedObject.radius" ng-change="radius(fabric.selectedObject.radius);"/>
            </md-input-container>
        </div>
        <div class="row form-group transparency" title="Spacing" ng-show="fabric.selectedObject.type == 'curvedText'" style="margin-bottom: 35px;">
            <md-input-container flex>
                <label for="Spacing">Spacing:</label>
                <input class='col-sm-12' title="Spacing" type='range' min="5" max="30" value="10" ng-model="fabric.selectedObject.spacing" ng-change="spacing(fabric.selectedObject.spacing);"/>
            </md-input-container>
        </div>

       <div class='row row-margin margin-top-20'>
            <div class="col-md-3">
                <h5>Copy Paste</h5>
                <md-button class="md-raised md-cornered" ng-click="copyItem()"><i class='fa fa-copy'></i> 
                    <md-tooltip md-visible="copy.showTooltip" md-direction="right">Copy</md-tooltip>
                </md-button>

                <md-button class="md-raised md-cornered" ng-click="pasteItem()"><i class='fa fa-paste'></i> 
                    <md-tooltip md-visible="paste.showTooltip" md-direction="right">Paste</md-tooltip>
                </md-button>
            </div>
            <div class="col-md-9">
                <h5>Swap</h5>
                <md-button class="md-raised md-cornered" ng-click="forwardSwap()"><i class='fa fa-mail-forward'></i> 
                    <md-tooltip md-visible="forward.showTooltip" md-direction="right">Forward Swap</md-tooltip>
                </md-button>
                <md-button class="md-raised md-cornered" ng-click="backwordSwap()"><i class='fa fa-mail-reply'></i> 
                    <md-tooltip md-visible="backward.showTooltip" md-direction="right">Backward Swap</md-tooltip>
                </md-button>
            </div>
        </div>

    </div>
</div>


<style>
.list-colors .bg-colors {
    border: 1px solid #CCCCCC;
    display: inline-block;
    height: 25px;
    margin: 2px;
    width: 25px;
}    
</style>



