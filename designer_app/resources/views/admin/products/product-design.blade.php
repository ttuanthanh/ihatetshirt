<tr>
    <td><div class="sort"><i class="fa fa-arrows-v"></i></div></td>
    <td>                            
        <a href="#colors-modal" class="p-color" data-toggle="modal"><div class="c"></div></a>
        <input type="hidden" class="form-control input-color" name="product[{{ $i }}][color]" value="">
    </td>
    <td>
        <input type="text" class="form-control input-color-title" name="product[{{ $i }}][color_title]" value="">
    </td>
    <td>
        <input type="text" class="form-control numeric text-right" name="product[{{ $i }}][price]" value="" maxlength="10">
    </td>

    <td class="text-center valign front">
        <input type="hidden" class="input-image" name="product[{{ $i }}][image][0][url]" value="">
        <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][0][attr]" value="">
        <input type="hidden" name="product[{{ $i }}][image][0][name]" value="front">
        <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="front">
        <img src="" class="img-thumb"><br>
        Configure</a>
    </td>
    <td class="text-center valign back">
        <input type="hidden" class="input-image" name="product[{{ $i }}][image][1][url]" value="">
        <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][1][attr]" value="">
        <input type="hidden" name="product[{{ $i }}][image][1][name]" value="back">
        <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="back">
        <img src="" class="img-thumb"><br>
        Configure</a>
    </td>
    <td class="text-center valign left">
        <input type="hidden" class="input-image" name="product[{{ $i }}][image][2][url]" value="">
        <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][2][attr]" value="">
        <input type="hidden" name="product[{{ $i }}][image][2][name]" value="left">
        <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="left">
        <img src="" class="img-thumb"><br>
        Configure</a>
    </td>
    <td class="text-center valign right">
        <input type="hidden" class="input-image" name="product[{{ $i }}][image][3][url]" value="">
        <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][3][attr]" value="">
        <input type="hidden" name="product[{{ $i }}][image][3][name]" value="right">
        <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="right">
        <img src="" class="img-thumb"><br>
        Configure</a>
    </td>

    <td class="text-center valign">
        <a href="javascript:void(0)" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-remove"></i></a>
    </td>
</tr>