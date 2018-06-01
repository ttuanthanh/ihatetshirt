<div class="tab-pane clearfix form-content" id="Text">

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="text_design">
            <div class="col-lg-12 thumb_listing">
                <div class="well" >
                        <h4>Add New Text</h4>

                    <div class="row form-group">
                        <md-input-container flex>
                            <label>Enter text here</label>
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
        <div role="tabpanel" class="tab-pane fade" id="word_cloud">
            <div class="col-lg-12 thumb_listing">
                <div class="well" >
                    <div class="row form-group">
                        <md-input-container flex>
                            <label>Enter words here</label>
                            <textarea  columns="1" id="textarea-text-word-cloud" style="text-align: {{ fabric.selectedObject.textAlign }}" ng-model="fabric.selectedObject.textWordCloud"></textarea>
                        </md-input-container>
                        <div class="clearfix">
                            <md-button class="md-raised md-cornered" ng-click="addWordCloud()" aria-label="Add Word Cloud"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Word Cloud</md-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>