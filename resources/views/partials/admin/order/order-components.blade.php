<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Components</h2>
</div>
<x-admin.section.card>
    <ul class="nav nav-pills otm-tab">
        <li class="nav-item col-6 col-md-3">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#order-accommodation">
                {{ Icon::accommodation() }} Accommodation
            </button>
        </li>
        <li class="nav-item col-6 col-md-3">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#order-activities">
                {{ Icon::activity() }} Activities
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#order-flights">
                {{ Icon::flight() }}
                Flights
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#order-transports">
                {{ Icon::transport() }}
                Transport
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#order-merchandise">
                {{ Icon::merchandise() }} Merchandise
            </button>
        </li>
    </ul>
    <div id="tables" class="tab-content otm-tab-content">
        <!-- accommodation Tab Panes -->
        <div id="order-accommodation" role="tabpanel" class="tab-pane fade show active">
            @include('partials.admin.order.component.accommodation')
        </div>
        <!-- Activities Tab Pane -->
        <div id="order-activities" role="tabpanel" class="tab-pane fade">
            @include('partials.admin.order.component.activity')            
        </div>
        <!-- flights Tab Pane -->
        <div id="order-flights" role="tabpanel" class="tab-pane fade">
            @include('partials.admin.order.component.flight') 
        </div>
        <!-- transports Tab Pane -->
        <div id="order-transports" role="tabpanel" class="tab-pane fade">
            @include('partials.admin.order.component.transport')
        </div>
        <div id="order-merchandise" role="tabpanel" class="tab-pane fade">
            @include('partials.admin.order.component.merchandise')
        </div>
    </div>
</x-admin.section.card>


