<table class="table table-hover datatable">
    <thead>
        <tr>
            <th width="1"></th>
            <th width="245">Name</th>
            <th width="100">SKU</th>
            <th width="90" class="text-right">Price</th>
            <th width="150">Categories</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rows as $row): ?>
        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
        <tr class="tr-row-lg has-row-actions">
            <td>
                @if( $postmeta->image )
                <a href="{{ $postmeta->image }}" class="btn-img-preview" data-title="{{ $row->post_title }}">
                    <img src="{{ has_image(str_replace('large', 'thumb', $postmeta->image)) }}" class="img-thumb"> 
                </a>
                @else
                <img src="{{ has_image() }}" class="img-thumb">
                @endif
            </td>
            <td>
                <a href="{{ URL::route($view.'.add', ['id' => $row->id]) }}" class="sbold select-product">{{ $row->post_title }}</a>

                <div class="row-actions">
                    <span class="text-muted">ID : {{ $row->id }}</span> | 
                    <a href="{{ URL::route($view.'.add', ['id' => $row->id]) }}" class="select-product">Select</a> 
                </div>
            </td>
            <td class="text-muted">{{ @$postmeta->sku }}</td>
            <td class="text-right">{{ amount_formatted(@$postmeta->starting_price) }}</td>
            <td class="text-muted">{{ $row->categoryList }}</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>