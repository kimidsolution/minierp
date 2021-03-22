<div class="modal fade" id="productModalInput" tabindex="-1" role="dialog" aria-labelledby="productModalInputLabel" aria-hidden="true">
    <form id="productModal" method="post" class="form-horizontal">
    @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="productModalInputLabel">Create Product/Services</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="label-required">Product Name</label>
                    {!! Form::text('product_name', null, ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'product_name']) !!}
                </div>
                <div class="form-group">
                    <label class="label-required">Product Category</label>
                    {!! Form::select('product_category', [], null, ['placeholder' => 'Select Product Category...', 'class' => 'select2 form-control mb-5 custom-select clear-input', 'id' => 'product_category', 'name' => 'product_category', 'style' => 'width: 100%;']) !!}
                </div>
                <div class="form-group">
                    <label class="label-required">SKU</label>
                    {!! Form::text('sku', null, ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'sku']) !!}
                </div>
                <div class="form-group">
                    <label class="label-required">Price</label>
                    {!! Form::text('price', null, ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'price']) !!}
                </div>                            
                <div class="form-group">
                    <label class="label-required">Type</label>
                    <div class="radio-buttons">
                        <label class="custom-radio">
                            <input type="radio" class="form-control" name="type" value="1" required checked>
                            <span class="radio-btn">
                            <i class="fa fa-check"></i>
                                <div class="option-check">
                                    <h3>Goods</h3>
                                </div>
                            </span>
                        </label>
                        <label class="custom-radio">
                            <input type="radio" class="form-control" name="type" value="2" required>
                            <span class="radio-btn">
                            <i class="fa fa-check"></i>
                            <div class="option-check">
                                <h3>Service</h3>
                            </div>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitModal">Save</button>
            </div>
            </div>
        </div>
    </form>
</div>