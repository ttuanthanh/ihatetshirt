<?php foreach($products as $product): ?>
<?php $postmeta = get_meta( $product->postMetas()->get() ); ?>        
<div class="img-thumb slick-thumb" onclick="angular.element('#productApp').scope().loadProduct('<?php echo $product->post_title; ?>', '<?php echo $postmeta->image; ?>', <?php echo $product->id; ?>, 0, 'USD');">
    <img src="<?php echo str_replace('large', 'thumb', $postmeta->image); ?>">
    <div class="prod-desc"><?php echo str_limit($product->post_title, 50); ?></div>     
</div>            
<?php endforeach; ?>


