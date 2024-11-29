<div class="otm-callout">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-12 row">
            <div class="row">
                <div class="col-12">
                    <p>First Name</p>
                    <h6 class="fw-bold">{{ $agent->first_name }}</h6>
                </div>
                <div class="col-6">
                    <p>Last Name</p>
                    <h6 class="fw-bold">{{ $agent->last_name }}</h6>
                </div>
                <div class="col-6">
                    <p>Email Address</p>
                    <h6 class="fw-bold">
                        @if(isset($agent->email))
                        <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                        @else
                        Email Address Not Set
                        @endif
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button onclick="openModal('admin.agent.form', {'agent': {{$agent->id}},})" class="btn btn-success">
                {{ Icon::edit() }}
                Edit Agent
            </button>
        </div>
    </div>
</div>