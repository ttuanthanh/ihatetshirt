
<div class="form-content">

<div class="row">
	<div class="col-md-6">
    <label class="sbold uppercase">
    	<input type="checkbox" name="add_name" class="toggle-team" data-target=".team-name" data-name="add-name">
    	Add Name	
    </label>

    <div class="row margin-top-10">
        <div class="col-md-3">
            <div class="list-color">
                <button class="dropdown-color btn btn-color btn-add-name" style="background-color:#000000;" data-color="#000000" data-target="add-name">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>        
        </div>
        <?php $name_font = App\Setting::get_setting('default_font'); ?>
        <div class="col-md-9">
            <div class="row form-group team-font font-add-name">
                <md-button class="md-raised md-cornered dropdown-toggle" data-toggle="dropdown" aria-label="Font Family"><span class='object-font-family-preview' style='font-family: "<?php echo $name_font; ?>";'><?php echo $name_font; ?></span> <span class="caret"></span></md-button>
                <ul class="dropdown-menu">
                    <li ng-repeat='font in fonts' 
                    data-name="add-name" 
                    ng-click="toggleTeamFont(font.name, 'add-name');" 
                    style='font-family: "{{ font.name }}";'> <a>{{ font.name }}</a> </li>
                </ul>
            </div>        
        </div>
    </div>
    <span class="text-add-name">Black</span>


	</div>
	<div class="col-md-6">

    <label class="sbold uppercase">
    	<input type="checkbox" name="add_number" class="toggle-team" data-target=".team-number" data-name="add-number">
    	Add Number	
    </label>

    <div class="row margin-top-10">
        <div class="col-md-3">
            <div class="list-color">
                <button class="dropdown-color btn btn-color btn-add-number" style="background-color:#000000;" data-color="#000000" data-target="add-number">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>        
        </div>
        <?php $name_font = App\Setting::get_setting('default_font'); ?>
        <div class="col-md-9">
            <div class="row form-group team-font font-add-number">
                <md-button class="md-raised md-cornered dropdown-toggle" data-toggle="dropdown" aria-label="Font Family"><span class='object-font-family-preview' style='font-family: "<?php echo $name_font; ?>";'><?php echo $name_font; ?></span> <span class="caret"></span></md-button>
                <ul class="dropdown-menu">
                    <li ng-repeat='font in fonts' 
                    data-name="add-number" 
                    ng-click="toggleTeamFont(font.name, 'add-number');" 
                    style='font-family: "{{ font.name }}";'><a>{{ font.name }}</a> 
                    </li>
                </ul>
            </div>        
        </div>
    </div>
    <span class="text-add-number">Black</span>


	</div>
</div>

<div class="row name-number-data">
    <div class="col-md-12">
        <hr>
        <h4>Name & Number Data</h4>    
    </div>

	<div class="col-md-5 team-name" style="display:none;">
		<h5>Name</h5>
	</div>
	<div class="col-md-3 team-number" style="display:none;">  
		<h5>Number</h5>
	</div>
	<div class="col-md-3">
		<h5>Size</h5>
	</div>	
</div>

    <form class="form-horizontal" role="form" id="form-team">

        <div class="mt-repeater name-number-data">
            <div data-repeater-list="team">

                <div data-repeater-item class="mt-repeater-item">
                    <div class="mt-repeater-row row">

                        <div class="col-md-5 team-name" style="display:none;">
                        	<input type="text" name="name" placeholder="" class="form-control"/>                               		
                        </div>
                        <div class="col-md-3 team-number" style="display:none;">       
                        	<input type="text" name="number" placeholder="00" class="form-control text-right"/>            
                        </div>
                        <div class="col-md-3">
                        	<?php echo Form::select('size', [], '', ['class' => 'form-control team-sizes']); ?>      
                        </div>
                        <div class="col-md-1">
                        	<a href="javascript:;" data-repeater-delete class=" mt-repeater-delete">
                        	<i class="fa fa-close"></i>
                        	</a>    	
                        </div>
        
    
                    </div>
                </div>

            </div>
            <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                <i class="fa fa-plus"></i> Add List Name & Number</a>
        </div>
    </form>  

</div>
