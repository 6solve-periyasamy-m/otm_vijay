@php /** @var \App\Models\Quote\Quote $quote */@endphp
<div>
    <ul class="nav nav-pills otm-tab">
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#summary">
                {{ Icon::list() }} {{ __('quotes.view.cards.components.tabs.summary') }}
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                {{ Icon::accommodation() }} {{ __('quotes.view.cards.components.tabs.accommodation') }}
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                {{ Icon::activity() }} {{ __('quotes.view.cards.components.tabs.activities') }}
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                {{ Icon::flight() }} {{ __('quotes.view.cards.components.tabs.flights') }}
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transport">
                {{ Icon::transport() }} {{ __('quotes.view.cards.components.tabs.transport') }}
            </button>
        </li>
        <li class="nav-item col-6 col-md-2">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#extras">
                {{ Icon::merchandise() }} {{ __('quotes.view.cards.components.tabs.extras') }}
            </button>
        </li>
    </ul>
    <div id="tables" class="tab-content otm-tab-content">
        <div id="summary" role="tabpanel" class="tab-pane fade show active">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.type') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->repository->getComponents() as $componentRepository)
                    <tr>
                        <td>
                            {{ ucwords($componentRepository->getComponentType()) }}
                        </td>
                        <td>
                            @if ($componentRepository->getComponentType() == 'merchandise')
                                {{ __('quotes.view.cards.components.common.na') }}
                            @else
                                {{ f_datetime($componentRepository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($componentRepository->getInventory()->getEndTime()) }}
                            @endif
                        </td>
                        <td>
                            {{ $componentRepository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $componentRepository->getPurchasePrice() !== null ? $componentRepository->getInventory()->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($componentRepository->getSalesPrice()) }} {{ $componentRepository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$componentRepository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$componentRepository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $componentRepository->getComponentType(), 'id' => $componentRepository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="accommodation" role="tabpanel" class="tab-pane fade">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->accommodation()->with('inventory')->get() as $component)
                    <tr>
                        <td>
                            {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                            to
                            {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                        </td>
                        <td>
                            {{ $component->repository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $component->repository->getPurchasePrice() !== null ? $component->inventory->repository->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="activities" role="tabpanel" class="tab-pane fade">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->activities()->with('inventory')->get() as $component)
                    <tr>
                        <td>
                            {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                            to
                            {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                        </td>
                        <td>
                            {{ $component->repository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $component->repository->getPurchasePrice() !== null ? $component->inventory->repository->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="flights" role="tabpanel" class="tab-pane fade">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->flights()->with('inventory')->get() as $component)
                    <tr>
                        <td>
                            {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                            to
                            {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                        </td>
                        <td>
                            {{ $component->repository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $component->repository->getPurchasePrice() !== null ? $component->inventory->repository->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="transport" role="tabpanel" class="tab-pane fade">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->transport()->with('inventory')->get() as $component)
                    <tr>
                        <td>
                            {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                            to
                            {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                        </td>
                        <td>
                            {{ $component->repository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $component->repository->getPurchasePrice() !== null ? $component->inventory->repository->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="extras" role="tabpanel" class="tab-pane fade">
            <table class="datatable autowidth-off table table-striped summary">
                <thead>
                <tr>
                    <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                    <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                    <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->merchandise()->with('inventory')->get() as $component)
                    <tr>
                        <td>
                            {{ $component->repository->__toString() }}
                        </td>
                        <td>
                            {{ $componentRepository->getQuantity() ?? "All Travellers" }}
                        </td>
                        <td>
                            {{ $component->repository->getPurchasePrice() !== null ? $component->inventory->repository->getPurchasePriceString() : 'Not Set' }}
                        </td>
                        <td>
                            {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                        </td>
                        <td>
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                            @endcan
                            <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                {{ Icon::list() }}
                            </a>
                            <form class="d-none merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                  action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                  method="post">
                                @csrf
                            </form>
                            <a href="javascript:$('.merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                {{ Icon::delete() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>