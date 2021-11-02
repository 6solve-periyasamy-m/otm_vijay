<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="name-input">Event Title</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <label for="description-input">Event Description</label>
                    <input name="description" value="{{ $description ?? "" }}" class="form-control"
                        id="description-input">
                </div>                
                <div class="form-group col-12">
                    <label for="starts_at-input">Event Start Date</label>
                    <input type="date" name="starts_at" value="{{ $starts_at ?? "" }}" class="form-control"
                        id="starts_at-input">
                </div>                
                <div class="form-group col-12">
                    <label for="ends_at-input">Event End Date</label>
                    <input type="date" name="ends_at" value="{{ $ends_at ?? "" }}" class="form-control" id="ends_at-input">
                </div>                
                <div class="form-group col-12">
                    <label for="booking_url-input">Booking Url</label>
                    <input name="booking_url" value="{{ $booking_url ?? "" }}" class="form-control" id="booking_url-input">
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
