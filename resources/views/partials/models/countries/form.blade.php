<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="name-input">Numeric Code</label>
                    <input name="name" value="{{ $numeric_code ?? "" }}" class="form-control" id="name-input">
                </div>
                <p></p><div class="form-group col-12">
                    <label for="name-input">Alpha Code</label>
                    <input name="name" value="{{ $alpha_code ?? "" }}" class="form-control" id="name-input">
                </div>
                <p></p><div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <label for="code-input">Dialing Code</label>
                    <input name="code" value="{{ $dialing_code ?? "" }}" class="form-control" id="code-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
