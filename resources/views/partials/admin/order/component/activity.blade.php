@php /** @var \App\Models\Order\Order $order; */ @endphp
<div id="activities-details">
    <div class="accordion" id="activitiesAccordion">
        @foreach($groupedActivities as $group)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $group['slug'] }}">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $group['slug'] }}"
                            aria-expanded="false"
                            aria-controls="collapse-{{ $group['slug'] }}">
                        {{ $group['name'] }}
                        &nbsp;<span class="badge rounded-pill text-bg-info">{{ $group['customers'] }} Customers</span>
                    </button>
                </h2>
                <div id="collapse-{{ $group['slug'] }}" class="accordion-collapse collapse"
                    aria-labelledby="heading-{{ $group['slug'] }}"
                    data-bs-parent="#activitiesAccordion">
                    <div class="accordion-body p-0">
                        <table class="table table-striped table-responsive-sm m-0">
                            <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Name</th>
                                <th>Activity Type</th>
                                <th>Ticket Type</th>
                                <th>Component Type</th>
                                <th>Cost</th>
                                <th>Purchase Price</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($group['items'] as $orderActivity)
                                @php $orderCustomer = $orderActivity->orderCustomer; @endphp
                                <tr component="{{ $orderActivity->id }}">
                                    <td style="min-width: 200px">
                                        {{ f_datetime($orderActivity->activity_inventory->starts_at) }} to
                                        {{ f_datetime($orderActivity->activity_inventory->ends_at) }}
                                    </td>
                                    <td>
                                        @can('read', \App\Models\Order\OrderCustomer::class)
                                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}"
                                            class="link-info">
                                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                                            </a>
                                        @else
                                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                                        @endcan
                                    </td>
                                    <td>{{ $orderActivity->activity->name }}</td>
                                    <td>{{ $orderActivity->activity->activityType->name }}</td>
                                    <td>{{ $orderActivity->activity_inventory->ticketType->name }}</td>
                                    <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                                    <td>
                                        {{ $orderActivity->tourComponent->tour_component_type === 'Included'
                                            ? f_currency(0)
                                            : f_currency($orderActivity->cost) }}
                                    </td>
                                    <td>
                                        {{ f_currency($orderActivity->purchase_price) }}
                                        @includeWhen($orderActivity->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @can('update', \App\Models\Order\Component\OrderActivity::class)
                                                <button onclick="openModal('admin.order.component.order-activity-form', {'component': {{ $orderActivity->id }} })"
                                                        class="btn btn-sm btn-outline-warning icon-height" title="Edit activity">
                                                    {{ Icon::edit() }}
                                                </button>
                                            @endcan
                                            <form action="{{ route('orderActivityDelete', ['id' => $orderActivity->id]) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="redirect"
                                                    value="{{ route(Route::currentRouteName(), ['order' => $order]) }}"/>
                                                <a href="#" onclick="this.parentNode.submit()"
                                                class="btn btn-outline-danger btn-sm" title="Delete activity">
                                                    {{ Icon::delete() }}
                                                </a>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
