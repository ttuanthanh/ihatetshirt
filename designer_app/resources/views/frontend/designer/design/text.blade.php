<div class="row">
    <div class="col-md-8">
        <textarea class="form-control" rows="4" placeholder="Enter text here ..."></textarea>                                            
    </div>
    <div class="col-md-4">
        <button class="btn btn-block btn-primary">Add</button>
    </div>
</div>

<div class="row margin-top-10">
    <div class="col-md-8">
        <select class="form-control select2">
            <option>Arial</option>
            <option>Arial Black</option>
            <option>Comic Sans MS</option>
            <option>Courier New</option>
            <option>Helvetica</option>
            <option>Impact</option>
            <option>Tahoma</option>
        </select>    
    </div>    
    <div class="col-md-4">
        <select class="form-control select2">
            <?php foreach(range(5, 20) as $row): ?>
            <option><?php echo $row; ?></option>
            <?php endforeach; ?>
        </select>        
    </div>
</div>

<div class="row margin-top-10">
    <div class="col-md-12">
        <button class="btn btn-default"><i class="fa fa-bold"></i></button>
        <button class="btn btn-default"><i class="fa fa-italic"></i></button>
        <button class="btn btn-default"><i class="fa fa-underline"></i></button>
        <button class="btn btn-default"><i class="fa fa-align-left"></i></button>
        <button class="btn btn-default"><i class="fa fa-align-center"></i></button>
        <button class="btn btn-default"><i class="fa fa-align-right"></i></button>
        <button class="btn btn-default"><i class="fa fa-align-justify"></i></button>                                                
    </div>
</div>


<div class="btn-group btn-group-justified margin-top-10">
    <a href="javascript:;" class="btn btn-default"> Left Chest</a>
    <a href="javascript:;" class="btn btn-default"> Right Chest </a>
    <a href="javascript:;" class="btn btn-default"> Full Print </a>
</div>