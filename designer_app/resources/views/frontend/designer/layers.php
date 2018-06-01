<div class="tab-pane clearfix" id="Layers">
    <h1>Layers</h1>

    <ul class="ul_layer_canvas sortable">
        <li ng-repeat="layer in objectLayers" class="ng-scope list-layer" data-id="{{layer.id}}">
            <span>{{layer.index}}</span>  

            <img ng-src="{{layer.src}}" alt=""/>
            <div class="f-right inner">

                <ul class="ulInner actions">
                    <li class="liActions"><a href="javascript:void(0)" ng-click="deleteObject(layer.object);" tabindex="0"><i class="fa fa-trash"></i></a></li>
                    
                    <li class="liActions" style="display:none;"><a href="javascript:void(0)" class="forward-{{layer.id}}" ng-click="objectForwardSwap(layer.object);" tabindex="0"><i class="fa fa-chevron-up"></i></a></li>
                    <li class="liActions" style="display:none;"><a href="javascript:void(0)" class="backward-{{layer.id}}" ng-click="objectBackwordSwap(layer.object);" tabindex="0"><i class="fa fa-chevron-down"></i></a></li>

                    <li class="liActions"><i class="fa fa-unsorted"></i></li>
                </ul>

            </div>
        </li>
    </ul>

</div>

