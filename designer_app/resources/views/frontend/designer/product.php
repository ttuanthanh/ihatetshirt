
<div class="category-select">
    Category
    <?php echo Form::select('category', ['all' => 'All Categories'] + $categories, '', ['class' => 'select2']); ?>     
</div>

<div class="text-center margin-top-30 product-loader" style="display:none;">
<img src="<?php echo asset('assets/uploads/loaders/4.gif'); ?>"  width="20%">    
</div>

<div class="product-colors"></div>

<div class="product-list slick">
    <?php 
    $sizes = '';

    foreach($products as $product): ?>
    <?php 
        $postmeta = get_meta( $product->postMetas()->get() ); 
        if( @$postmeta->size ) {      
            $sizes = json_encode(array_pluck(json_decode($postmeta->size, true), 'name'));
        }
    ?>        
    <div class="slick-thumb img-thumb" data-id="<?php echo $product->id; ?>" data-sizes='<?php echo $sizes; ?>'>
        <img src="<?php echo has_image(str_replace('large', 'thumb', $postmeta->image)); ?>">
        <div class="prod-desc"><?php echo str_limit($product->post_title, 50); ?></div>     
    </div>            
    <?php endforeach; ?>
</div>


<style>
.colors {
    background: #fbfbfb;
    padding: 10px 10px 5px;
    margin-top: 20px;
    border: 1px solid #eaeaea;    
}
.select2 { width: 200px !important; }
.product-list {
    margin-top: 20px;
}
.colors li {
    display: inline-block;
    margin: 1px 3px;
}    
.bg-color {
    border: 1px solid #CCCCCC;
    height: 25px;
    width: 25px;
    display: block;
    cursor: pointer;
}
.bg-color.selected {
    background-color: #5c5c5c;
    border-radius: 20px;
    box-shadow: 0px 0px 10px 0 #000;
    outline: 0 !important; 
}
</style>


