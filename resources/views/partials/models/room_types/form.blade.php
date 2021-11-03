<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <label for="maximum_occupancy-input">Maximum Occupancy</label>
                    <input name="maximum_occupancy" value="{{ $maximum_occupancy ?? "" }}" class="form-control"
                        id="maximum_occupancy-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
