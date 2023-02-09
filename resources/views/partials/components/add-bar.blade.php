<div class="d-inline-flex mb-3 col-12">
    <div class="col-xl-10">
        <select class="form-select {{$component}}-component-type-select">
            <option value="Included" selected>Included</option>
            <option value="Add-on">Add-on</option>
        </select>
    </div>
    <div class="col-xl-2">
        <a href="{{$href}}" class="btn btn-primary ms-3 text-white">
            {{ Icon::create() }}
            <span>Add Selected Rows</span>
        </a>
    </div>
</div>
