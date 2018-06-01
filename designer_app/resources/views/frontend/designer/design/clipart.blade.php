

<div class="input-group">
    <div class="input-icon">
        <i class="fa fa-search"></i>
        <input id="newpassword" class="form-control" type="text" name="password" placeholder="Enter Search ..."> </div>
    <span class="input-group-btn">
        <button id="genpassword" class="btn btn-success" type="button">Search</button>
    </span>
</div>

<div class="product-list margin-top-20">
    <?php foreach(range(1, 50) as $row): ?>
    <div class="img-thumb">
        <img src="http://via.placeholder.com/300x300">
    </div>                                                
    <?php endforeach; ?>
</div>