<?php foreach($products as $product): ?>
<?php $postmeta = get_meta( $product->postMetas()->get() ); ?>        
<div class="img-thumb slick-thumb" data-id="{{ $product->id }}">
    <img src="<?php echo has_image(str_replace('large', 'thumb', $postmeta->image)); ?>">
    <div class="prod-desc"><?php echo str_limit($product->post_title, 50); ?></div>     
</div>            
<?php endforeach; ?>


