
@extends('layouts.master')
@section('title', (isset($slot) ? 'Edit Slot' : 'Add Slot') . ' - Super Admin')

@section('content')
@php
    $slot = $slot ?? null;
    $drawTimeValue = null;
    $titleValue = old('title', $slot?->title);
    $titleParts = array_values(array_filter(array_map('trim', explode(',', (string) $titleValue)), fn ($v) => $v !== ''));
    $titleParts = array_values(array_unique(array_filter($titleParts, fn ($v) => preg_match('/^[1-5]$/', (string) $v) === 1)));
    $titleCount = (int) ($titleParts[0] ?? $titleValue);
    if ($titleCount < 1 || $titleCount > 5) {
        $titleCount = 1;
    }

    $itemTitleCounts = [];
    if (isset($slot) && $slot->items && $slot->items->count()) {
        $itemTitleCounts = $slot->items
            ->pluck('title')
            ->filter(fn ($v) => in_array((int) $v, [1, 2, 3, 4, 5], true))
            ->map(fn ($v) => (string) $v)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    $selectedCounts = array_values(array_unique(array_merge(
        count($titleParts) ? $titleParts : [(string) $titleCount],
        $itemTitleCounts
    )));

    if (empty($selectedCounts) && $titleCount >= 1 && $titleCount <= 5) {
        $selectedCounts = [(string) $titleCount];
    }

    if (isset($slot) && !empty($slot->draw_time)) {
        try {
            $drawTimeValue = \Illuminate\Support\Carbon::parse($slot->draw_time)->format('Y-m-d\TH:i');
        } catch (\Throwable $e) {
            $drawTimeValue = $slot->draw_time;
        }
    }
@endphp
<div class="container-xxl flex-grow-1 container-p-y mx-auto" style="max-width: 75%;">
    @if (session('success') || session('danger'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show mb-5"
                role="alert">
                <strong>{{ session('success') ? session('success') : session('danger') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <div class="row">
        <div class="col-xxl">

            <div class="card mb-6">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ isset($slot) ? 'Edit Slot' : 'Add Slot' }}</h5>
                </div>

                <div class="card-body">

                    <form id="slotForm"
                          method="POST"
                          novalidate
                          data-is-edit="{{ isset($slot) ? 1 : 0 }}"
                          action="{{ isset($slot) ? route('admin.slots.edit', $slot->slug) : route('admin.slots.add') }}">
                        @csrf

                        <div class="row mb-4">

                            <div class="col-md-2">
                                <label class="form-label">Main Title <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="main_title"
                                       class="form-control"
                                       required
                                       value="{{ old('main_title', $slot?->main_title ?? '') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Draw Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="draw_date"
                                       class="form-control"
                                       required
                                      value="{{ old('draw_date', $slot?->draw_date ?? '') }}"
                                        {{ isset($slot) ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Draw Time <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="draw_time"
                                       class="form-control"
                                       required
                                        value="{{ old('draw_time', $slot?->draw_time ?? '') }}"
                                        {{ isset($slot) ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Booking Close time <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="booking_close_time"
                                       class="form-control"
                                       required
                                        value="{{ old('booking_close_time', $slot?->booking_close_time ?? '') }}"
                                    {{ isset($slot) ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Short Title <span class="text-danger">*</span></label>
                               <input type="text"
                                       name="short_title"
                                       class="form-control"
                                       required
                                       value="{{ old('short_title', $slot?->short_title ?? '') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Commission (%) <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="commission"
                                       class="form-control"
                                       required
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       value="{{ old('commission', $slot?->commission ?? '0.00') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="hidden" id="title" name="title" value="{{ old('title', $slot?->title) }}">
                                <select class="form-select" id="title_multi" name="title_multi[]" multiple size="5" required>
                                    <option value="1" {{ in_array('1', $selectedCounts) ? 'selected' : '' }}>Single Digit</option>
                                    <option value="2" {{ in_array('2', $selectedCounts) ? 'selected' : '' }}>Double Digit</option>
                                    <option value="3" {{ in_array('3', $selectedCounts) ? 'selected' : '' }}>Three Digit</option>
                                    <option value="4" {{ in_array('4', $selectedCounts) ? 'selected' : '' }}>Four Digit</option>
                                    {{-- <option value="5" {{ in_array('5', $selectedCounts) ? 'selected' : '' }}>Five Digit</option> --}}
                                </select>

                            </div>

                        </div>

                        <hr>

                        <div id="slotItemsSection" class="{{ $titleValue ? '' : 'd-none' }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Slot Items</h5>
                            </div>

                            <div id="slotItemsWrapper">
                                <div id="slotItemsMultiContainer"></div>

                            <div id="slotItemsServerContainer">
                            @if(isset($slot) && $slot->items && $slot->items->count())
                                @php
                                    $rowsToShow = 3;
                                    $itemsByCount = [];
                                    foreach ($selectedCounts as $cntStr) {
                                        $n = (int) $cntStr;
                                        if ($n < 1 || $n > 5) continue;

                                        $itemsByCount[$n] = $slot->items
                                            ->sortBy('slot_items_id')
                                            ->filter(function ($it) use ($n) {
                                                $digitStr = (string) ($it?->digit ?? '');
                                                $digitStr = ltrim($digitStr, '-');
                                                return strlen($digitStr) === $n;
                                            })
                                            ->values();
                                    }
                                @endphp
                                @foreach($itemsByCount as $cnt => $sectionItems)
                                    @php
                                        $rowsToShow = max(3, $sectionItems->count());
                                        $chunks = $sectionItems->chunk((int) $cnt)->take($rowsToShow);
                                        $sectionWin = old("win_amount_by_title.$cnt", $sectionItems->first()?->win_amount ?? '');
                                        $sectionTicket = old("ticket_amt_by_title.$cnt", $sectionItems->first()?->ticket_amt ?? '');
                                    @endphp

                                    <div class="mb-4 slot-title-section" data-title="{{ $cnt }}">
                                        <h6 class="mb-3">Slot Items - {{ $cnt == 1 ? 'Single Digit' : ($cnt == 2 ? 'Double Digit' : ($cnt == 3 ? 'Three Digit' : ($cnt == 4 ? 'Four Digit' : 'Five Digit'))) }}</h6>
                                        @if($cnt == 3)
                                            <h6 class="mb-3">Three Digits</h6>
                                        @endif
                                        @if($cnt != 3)
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <label class="form-label">Win Amount <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                           name="win_amount_by_title[{{ $cnt }}]"
                                                           class="form-control section-win-amount"
                                                           required
                                                           value="{{ $sectionWin }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Ticket Amount <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                           name="ticket_amt_by_title[{{ $cnt }}]"
                                                           class="form-control section-ticket-amount"
                                                           required
                                                           value="{{ $sectionTicket }}">
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            $sectionRows = $sectionItems->take($rowsToShow);
                                        @endphp
                                        @foreach($sectionRows as $item)
                                            <div class="slot-item row mb-4">
                                                @php
                                                    $chunkIds = [$item->slot_items_id];
                                                    $rawColor = $item?->color ?? '';
                                                    $colorVal = preg_match('/^#?[0-9a-fA-F]{6}$/', $rawColor) ? ('#' . ltrim($rawColor, '#')) : '#000000';
                                                @endphp
                                                @if($cnt == 3)
                                                    <div class="col-md-2">
                                                        <label>Ticket Amount <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="ticket_amt[]"
                                                               class="form-control"
                                                               required
                                                                value="{{ $item?->ticket_amt ?? '' }}"
                                                                placeholder="Ticket Amount">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>First Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="first_price[]"
                                                               class="form-control"
                                                               required
                                                               value="{{ $item?->first_price ?? '' }}"
                                                               placeholder="First Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Second Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="second_price[]"
                                                               class="form-control"
                                                               required
                                                               value="{{ $item?->second_price ?? '' }}"
                                                               placeholder="Second Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Third Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="third_price[]"
                                                               class="form-control"
                                                               required
                                                               value="{{ $item?->third_price ?? '' }}"
                                                               placeholder="Third Price">
                                                    </div>
                                                @else
                                                    <input type="hidden" name="win_amount[]" class="row-win-amount" value="{{ $sectionWin }}">
                                                    <input type="hidden" name="ticket_amt[]" class="row-ticket-amount" value="{{ $sectionTicket }}">
                                                    <input type="hidden" name="first_price[]" value="">
                                                    <input type="hidden" name="second_price[]" value="">
                                                    <input type="hidden" name="third_price[]" value="">
                                                @endif

                                                <div class="col-md-2">
                                                    <label>Group Name <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="slot_item_id[]" value="{{ $item->slot_items_id }}">
                                                    <input type="hidden" name="item_title_count[]" value="{{ $cnt }}">
                                                    <input type="text"
                                                           name="group_name[]"
                                                           class="form-control"
                                                           required
                                                           value="{{ $item?->group_name ?? '' }}"
                                                           placeholder="A">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Digit <span class="text-danger">*</span></label>
                                                    <input type="number"
                                                           name="digit[]"
                                                           class="form-control"
                                                           required
                                                           value="{{ $item?->digit ?? '' }}"
                                                           placeholder="2"
                                                           maxlength="{{ $cnt }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Color</label>
                                                    <input type="color"
                                                           name="color[]"
                                                           class="form-control"
                                                           value="{{ $colorVal }}">
                                                </div>

                                                <div class="col-md-2 d-flex align-items-start pt-4">
                                                    <button type="button" class="btn btn-success addItemBtn me-2">+</button>
                                                    <button type="button"
                                                            class="btn btn-danger removeItemBtn"
                                                            data-item-ids='@json($chunkIds)'>
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        @php $renderedSectionRows = $sectionRows->count(); @endphp
                                        @for($r = $renderedSectionRows; $r < $rowsToShow; $r++)
                                            <div class="slot-item row mb-4">
                                                @if($cnt == 3)
                                                    <div class="col-md-2">
                                                        <label>Ticket Amount <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="ticket_amt[]"
                                                               class="form-control"
                                                               required
                                                               placeholder="Ticket Amount">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>First Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="first_price[]"
                                                               class="form-control"
                                                               required
                                                               placeholder="First Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Second Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="second_price[]"
                                                               class="form-control"
                                                               required
                                                               placeholder="Second Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Third Price <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                               name="third_price[]"
                                                               class="form-control"
                                                               required
                                                               placeholder="Third Price">
                                                    </div>
                                                @else
                                                    <input type="hidden" name="win_amount[]" class="row-win-amount" value="{{ $sectionWin }}">
                                                    <input type="hidden" name="ticket_amt[]" class="row-ticket-amount" value="{{ $sectionTicket }}">
                                                    <input type="hidden" name="first_price[]" value="">
                                                    <input type="hidden" name="second_price[]" value="">
                                                    <input type="hidden" name="third_price[]" value="">
                                                @endif

                                                <div class="col-md-2">
                                                    <label>Group Name <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="slot_item_id[]" value="">
                                                    <input type="hidden" name="item_title_count[]" value="{{ $cnt }}">
                                                    <input type="text"
                                                           name="group_name[]"
                                                           class="form-control"
                                                           required
                                                           placeholder="Group Name">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Digit <span class="text-danger">*</span></label>
                                                    <input type="number"
                                                           name="digit[]"
                                                           class="form-control"
                                                           required
                                                           placeholder="Digit"
                                                           maxlength="{{ $cnt }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Color</label>
                                                    <input type="color"
                                                           name="color[]"
                                                           class="form-control"
                                                           value="#000000">
                                                </div>

                                                <div class="col-md-2 d-flex align-items-start pt-4">
                                                    <button type="button" class="btn btn-success addItemBtn me-2">+</button>
                                                    <button type="button"
                                                            class="btn btn-danger removeItemBtn">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                @endforeach
                            @else
                                @if($titleCount != 3)
                                    <div class="row mb-3 slot-title-section" data-title="{{ $titleCount }}">
                                        <div class="col-md-2">
                                            <label class="form-label">Win Amount <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="win_amount_by_title[{{ $titleCount }}]"
                                                   class="form-control section-win-amount"
                                                   required
                                                   value="{{ old("win_amount_by_title.$titleCount", '') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Ticket Amount <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="ticket_amt_by_title[{{ $titleCount }}]"
                                                   class="form-control section-ticket-amount"
                                                   required
                                                   value="{{ old("ticket_amt_by_title.$titleCount", '') }}">
                                        </div>
                                    </div>
                                @else
                                    <h6 class="mb-3">Three Digits</h6>
                                @endif
                                @for($r = 0; $r < 3; $r++)
                                    <div class="slot-item row mb-4">
                                        @if($titleCount == 3)
                                            <div class="col-md-2">
                                                <label>Ticket Amount <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       name="ticket_amt[]"
                                                       class="form-control"
                                                       required
                                                       placeholder="Ticket Amount">
                                            </div>
                                            <div class="col-md-2">
                                                <label>First Price <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       name="first_price[]"
                                                       class="form-control"
                                                       required
                                                       placeholder="First Price">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Second Price <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       name="second_price[]"
                                                       class="form-control"
                                                       required
                                                       placeholder="Second Price">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Third Price <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       name="third_price[]"
                                                       class="form-control"
                                                       required
                                                       placeholder="Third Price">
                                            </div>
                                        @else
                                            <input type="hidden" name="win_amount[]" class="row-win-amount" value="{{ old("win_amount_by_title.$titleCount", '') }}">
                                            <input type="hidden" name="ticket_amt[]" class="row-ticket-amount" value="{{ old("ticket_amt_by_title.$titleCount", '') }}">
                                            <input type="hidden" name="first_price[]" value="">
                                            <input type="hidden" name="second_price[]" value="">
                                            <input type="hidden" name="third_price[]" value="">
                                        @endif

                                        <div class="col-md-2">
                                            <label>Group Name <span class="text-danger">*</span></label>
                                            <input type="hidden" name="slot_item_id[]" value="">
                                            <input type="hidden" name="item_title_count[]" value="{{ $titleCount }}">
                                            <input type="text"
                                                   name="group_name[]"
                                                   class="form-control"
                                                   required
                                                   placeholder="Group Name">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Digit <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   name="digit[]"
                                                   class="form-control"
                                                   required
                                                   placeholder="Digit"
                                                   maxlength="{{ $titleCount }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Color</label>
                                            <input type="color"
                                                   name="color[]"
                                                   class="form-control"
                                                   value="#000000">
                                        </div>

                                        <div class="col-md-2 d-flex align-items-start pt-4">
                                            <button type="button" class="btn btn-success addItemBtn me-2">+</button>
                                            <button type="button"
                                                    class="btn btn-danger removeItemBtn">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                            </div>

                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                {{ isset($slot) ? 'Update Slot' : 'Save Slot' }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function(){

     let today = new Date().toISOString().split('T')[0];

    $('input[name="draw_date"]').attr('min', today);

    const oldWinAmounts = @json(old('win_amount', []));
    const oldTicketAmounts = @json(old('ticket_amt', []));
    const oldFirstPrices = @json(old('first_price', []));
    const oldSecondPrices = @json(old('second_price', []));
    const oldThirdPrices = @json(old('third_price', []));
    const oldWinByTitle = @json(old('win_amount_by_title', []));
    const oldTicketByTitle = @json(old('ticket_amt_by_title', []));
    const oldSlotItemIds = @json(old('slot_item_id', []));
    const oldItemTitleCounts = @json(old('item_title_count', []));
    const oldGroupNames = @json(old('group_name', []));
    const oldDigits = @json(old('digit', []));
    const oldColors = @json(old('color', []));

    function buildSectionAmountsFromServerDom(fieldName) {
        const values = {};
        const $container = $('#slotItemsServerContainer');
        if (!$container.length) return values;

        $container.find(`input[name^="${fieldName}["]`).each(function () {
            const name = $(this).attr('name') || '';
            const match = name.match(/\[(\d+)\]/);
            if (!match) return;
            const index = match[1];
            const val = $(this).val();
            if (val !== undefined && val !== null && String(val).trim() !== '') {
                values[index] = val;
            }
        });

        return values;
    }

    const serverWinByTitle = buildSectionAmountsFromServerDom('win_amount_by_title');
    const serverTicketByTitle = buildSectionAmountsFromServerDom('ticket_amt_by_title');
    const mergedWinByTitle = { ...serverWinByTitle, ...oldWinByTitle };
    const mergedTicketByTitle = { ...serverTicketByTitle, ...oldTicketByTitle };

    function buildBucketsFromServerDom() {
        const buckets = {1: [], 2: [], 3: [], 4: [], 5: []};
        const $container = $('#slotItemsServerContainer');
        if (!$container.length) return buckets;

        const $groups = $container.find('input[name="group_name[]"]');
        const $digits = $container.find('input[name="digit[]"]');
        const $colors = $container.find('input[name="color[]"]');
        const $wins = $container.find('input[name="win_amount[]"]');
        const $tickets = $container.find('input[name="ticket_amt[]"]');
        const $firstPrices = $container.find('input[name="first_price[]"]');
        const $secondPrices = $container.find('input[name="second_price[]"]');
        const $thirdPrices = $container.find('input[name="third_price[]"]');
        const $tcs = $container.find('input[name="item_title_count[]"]');
        const $ids = $container.find('input[name="slot_item_id[]"]');

        const max = Math.max($groups.length, $digits.length, $colors.length, $wins.length, $tickets.length, $firstPrices.length, $secondPrices.length, $thirdPrices.length, $tcs.length, $ids.length);
        for (let i = 0; i < max; i++) {
            const tc = parseInt(String($tcs.eq(i).val() || ''), 10);
            if (![1, 2, 3, 4, 5].includes(tc)) continue;

            const winAmount = $wins.eq(i).val() || '';
            const ticketAmt = $tickets.eq(i).val() || '';
            const firstPrice = $firstPrices.eq(i).val() || '';
            const secondPrice = $secondPrices.eq(i).val() || '';
            const thirdPrice = $thirdPrices.eq(i).val() || '';
            const group = $groups.eq(i).val() || '';
            const digit = $digits.eq(i).val() || '';
            const colorRaw = $colors.eq(i).val() || '';
            const color = String(colorRaw).trim() || '#000000';
            const colorVal = /^#?[0-9a-fA-F]{6}$/.test(color) ? ('#' + color.replace(/^#/, '')) : '#000000';
            const id = $ids.eq(i).val() || '';

            buckets[tc].push({ id, winAmount, ticketAmt, firstPrice, secondPrice, thirdPrice, group, digit, color: colorVal });
        }

        return buckets;
    }

    const oldBuckets = (function buildOldBuckets() {
        const domBuckets = buildBucketsFromServerDom();
        const hasOldData = (oldGroupNames?.length || 0) + (oldDigits?.length || 0) + (oldColors?.length || 0) + (oldWinAmounts?.length || 0) + (oldTicketAmounts?.length || 0) + (oldFirstPrices?.length || 0) + (oldSecondPrices?.length || 0) + (oldThirdPrices?.length || 0) + (oldItemTitleCounts?.length || 0) + (oldSlotItemIds?.length || 0) > 0;
        if (!hasOldData) {
            return domBuckets;
        }

        const buckets = {1: [], 2: [], 3: [], 4: [], 5: []};
        const max = Math.max(
            oldGroupNames?.length || 0,
            oldDigits?.length || 0,
            oldColors?.length || 0,
            oldWinAmounts?.length || 0,
            oldTicketAmounts?.length || 0,
            oldFirstPrices?.length || 0,
            oldSecondPrices?.length || 0,
            oldThirdPrices?.length || 0,
            oldItemTitleCounts?.length || 0,
            oldSlotItemIds?.length || 0,
        );

        for (let i = 0; i < max; i++) {
            const tc = parseInt(String(oldItemTitleCounts?.[i] ?? ''), 10);
            if (![1, 2, 3, 4, 5].includes(tc)) continue;

            const winAmount = oldWinAmounts?.[i] ?? '';
            const ticketAmt = oldTicketAmounts?.[i] ?? '';
            const firstPrice = oldFirstPrices?.[i] ?? '';
            const secondPrice = oldSecondPrices?.[i] ?? '';
            const thirdPrice = oldThirdPrices?.[i] ?? '';
            const group = oldGroupNames?.[i] ?? '';
            const digit = oldDigits?.[i] ?? '';
            const colorRaw = oldColors?.[i] ?? '';
            const color = String(colorRaw || '').trim() || '#000000';
            const colorVal = /^#?[0-9a-fA-F]{6}$/.test(color) ? ('#' + color.replace(/^#/, '')) : '#000000';
            const id = oldSlotItemIds?.[i] ?? '';

            buckets[tc].push({ id, winAmount, ticketAmt, firstPrice, secondPrice, thirdPrice, group, digit, color: colorVal });
        }

        return buckets;
    })();

    function getTitleCount() {
        const raw = String($('#title').val() || '');
        const parts = raw
            .split(',')
            .map(v => String(v).trim())
            .filter(v => v !== '');
        return parts.length;
    }

    function syncTitleFromMulti() {
        const selected = $('#title_multi').val() || [];
        const normalized = selected
            .map(v => String(v).trim())
            .filter(v => v !== '')
            .map(v => parseInt(v, 10))
            .filter(v => [1, 2, 3, 4, 5].includes(v))
            .filter((v, idx, arr) => arr.indexOf(v) === idx)
            .sort((a, b) => a - b);

        $('#title').val(normalized.join(','));
    }

    function renderSelectedTitleSections() {
        const selected = $('#title_multi').val() || [];

        if (!selected.length) {
            $('#slotItemsMultiContainer').empty();
            $('#slotItemsSection').addClass('d-none');
            return;
        }

        $('#slotItemsSection').removeClass('d-none');
        const cursors = {1: 0, 2: 0, 3: 0, 4: 0, 5: 0};

        const labels = {
            1: 'Single Digit',
            2: 'Double Digit',
            3: 'Three Digit',
            4: 'Four Digit',
            5: 'Five Digit',
        };

        let html = '';
        selected.forEach(function (val) {
            const count = parseInt(String(val), 10);
            if (![1, 2, 3, 4, 5].includes(count)) return;

            const titleText = labels[count] || `Title ${count}`;
            const winVal = mergedWinByTitle[count] ?? '';
            const ticketVal = mergedTicketByTitle[count] ?? '';
            const sectionAmountsHtml = count === 3 ? '' : `
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Win Amount <span class="text-danger">*</span></label>
                        <input type="text" name="win_amount_by_title[${count}]" class="form-control section-win-amount" value="${String(winVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ticket Amount <span class="text-danger">*</span></label>
                        <input type="text" name="ticket_amt_by_title[${count}]" class="form-control section-ticket-amount" value="${String(ticketVal).replace(/"/g, '&quot;')}" required>
                    </div>
                </div>`;

            html += `
                <div class="mb-4 slot-title-section" data-title="${count}">
                    <h6 class="mb-3">Slot Items - ${titleText}</h6>
                    ${count === 3 ? '<h6 class="mb-3">Three Digits</h6>' : ''}
                    ${sectionAmountsHtml}
                    ${buildSlotItemsHtml(count, cursors)}
                </div>
            `;
        });

        $('#slotItemsMultiContainer').html(html);
        syncSectionHiddenAmounts();
    }

    function buildSlotItemsHtml(count, cursors) {
        let html = '';
        const bucket = oldBuckets[count] || [];
        const rowsToShow = Math.max(3, bucket.length);
        for (let r = 0; r < rowsToShow; r++) {
            const pos = (cursors && Number.isInteger(cursors[count])) ? cursors[count] : 0;
            const existing = bucket[pos] || null;
            if (cursors && Number.isInteger(cursors[count])) cursors[count]++;
            const idVal = existing ? (existing.id ?? '') : '';
            const winVal = existing ? (existing.winAmount ?? '') : '';
            const ticketVal = existing ? (existing.ticketAmt ?? '') : '';
            const firstPriceVal = existing ? (existing.firstPrice ?? '') : '';
            const secondPriceVal = existing ? (existing.secondPrice ?? '') : '';
            const thirdPriceVal = existing ? (existing.thirdPrice ?? '') : '';
            const gVal = existing ? (existing.group ?? '') : '';
            const dVal = existing ? (existing.digit ?? '') : '';
            const cVal = existing ? (existing.color ?? '#000000') : '#000000';
            const itemIds = idVal ? JSON.stringify([idVal]) : '[]';
            const amountFieldsHtml = count === 3 ? `
                    <div class="col-md-2">
                        <label>Ticket Amount <span class="text-danger">*</span></label>
                        <input type="text" name="ticket_amt[]" class="form-control" placeholder="Ticket Amount" value="${String(ticketVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label>First Price <span class="text-danger">*</span></label>
                        <input type="text" name="first_price[]" class="form-control" placeholder="First Price" value="${String(firstPriceVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Second Price <span class="text-danger">*</span></label>
                        <input type="text" name="second_price[]" class="form-control" placeholder="Second Price" value="${String(secondPriceVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Third Price <span class="text-danger">*</span></label>
                        <input type="text" name="third_price[]" class="form-control" placeholder="Third Price" value="${String(thirdPriceVal).replace(/"/g, '&quot;')}" required>
                    </div>` : `
                    <input type="hidden" name="win_amount[]" class="row-win-amount" value="${String(mergedWinByTitle[count] ?? '').replace(/"/g, '&quot;')}">
                    <input type="hidden" name="ticket_amt[]" class="row-ticket-amount" value="${String(mergedTicketByTitle[count] ?? '').replace(/"/g, '&quot;')}">
                    <input type="hidden" name="first_price[]" value="">
                    <input type="hidden" name="second_price[]" value="">
                    <input type="hidden" name="third_price[]" value="">`;

            html += `
                <div class="slot-item row mb-4">
                    ${amountFieldsHtml}
                    <div class="col-md-2">
                        <label>Group Name <span class="text-danger">*</span></label>
                        <input type="hidden" name="slot_item_id[]" value="${String(idVal).replace(/"/g, '&quot;')}">
                        <input type="hidden" name="item_title_count[]" value="${count}">
                        <input type="text" name="group_name[]" class="form-control" placeholder="Group Name" value="${String(gVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Digit <span class="text-danger">*</span></label>
                        <input type="number" name="digit[]" class="form-control" placeholder="Digit" maxlength="${count}" value="${String(dVal).replace(/"/g, '&quot;')}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Color</label>
                        <input type="color" name="color[]" class="form-control" value="${String(cVal).replace(/"/g, '&quot;')}">
                    </div>
                    <div class="col-md-2 d-flex align-items-start pt-4">
                        <button type="button" class="btn btn-success addItemBtn me-2">+</button>
                        <button type="button" class="btn btn-danger removeItemBtn" data-item-ids='${itemIds}'>Remove</button>
                    </div>
                </div>`;
        }
        return html;
    }

    function updateHiddenSectionInputs() {
        const slotItemsHidden = $('#slotItemsSection').hasClass('d-none');
        $('#slotItemsSection').find('input, select, textarea').prop('disabled', slotItemsHidden);

        const serverContainerHidden = $('#slotItemsServerContainer').is(':hidden');
        $('#slotItemsServerContainer').find('input, select, textarea').prop('disabled', serverContainerHidden);
    }

    function syncSectionHiddenAmounts() {
        $('.slot-title-section').each(function () {
            const $section = $(this);
            const title = parseInt(String($section.data('title') || ''), 10);
            if (title === 3) return;

            const win = $section.find('.section-win-amount').first().val() || '';
            const ticket = $section.find('.section-ticket-amount').first().val() || '';
            $section.find('.row-win-amount').val(win);
            $section.find('.row-ticket-amount').val(ticket);
        });
    }

    function toggleSlotItemsSection() {
        const count = getTitleCount();
        if (count > 0) {
            $('#slotItemsSection').removeClass('d-none');
        } else {
            $('#slotItemsSection').addClass('d-none');
        }
        updateHiddenSectionInputs();
    }

    function clearValidationErrors() {
        $('#slotForm .is-invalid').removeClass('is-invalid');
        $('#slotForm .validation-error').remove();
    }

    function showFieldError(fieldKey, message) {
        // Laravel may send: main_title, draw_time, group_name.0, digit.1, etc.
        const parts = String(fieldKey).split('.');
        const base = parts[0];
        const index = (parts.length > 1 && parts[1] !== '') ? parseInt(parts[1], 10) : null;

        let $input = $('#slotForm').find(`[name="${base}"]`);

        if (!$input.length) {
            // Handle bracket style inputs if the backend returns one.
            if (Number.isInteger(index) && index >= 0) {
                $input = $('#slotForm').find(`[name="${base}[${index}]"]`);
            }
        }

        if (!$input.length) {
            // For array inputs in blade: name="group_name[]"
            const arrayName = `${base}[]`;
            const $inputs = $('#slotForm').find(`[name="${arrayName}"]`);
            if ($inputs.length) {
                $input = (Number.isInteger(index) && index >= 0) ? $inputs.eq(index) : $inputs.first();
            }
        }

        if (!$input.length) return;

        $input.addClass('is-invalid');
        const $error = $(`<div class="text-danger small mt-1 validation-error"></div>`).text(message);
        $error.insertAfter($input);
    }

    // Remove error once user edits the field
    $(document).on('input change', '#slotForm input, #slotForm select, #slotForm textarea', function () {
        $(this).removeClass('is-invalid');
        $(this).siblings('.validation-error').remove();
        $(this).next('.validation-error').remove();
    });

    $(document).on('input change', '.section-win-amount, .section-ticket-amount', function () {
        syncSectionHiddenAmounts();
    });

    // Slot items show/hide based on Title (multi-select)
    $('#title_multi').on('change', function () {
        syncTitleFromMulti();
        toggleSlotItemsSection();
        const isEdit = String($('#slotForm').data('is-edit') || '0') === '1';
        $('#slotItemsServerContainer').hide();
        renderSelectedTitleSections();
        updateHiddenSectionInputs();
    });

    // Initialize multi-select from hidden title value
    (function initTitleMulti() {
        const isEdit = String($('#slotForm').data('is-edit') || '0') === '1';
        const current = String($('#title').val() || '');
        const parts = current
            .split(',')
            .map(v => String(v).trim())
            .filter(v => v !== '')
            .map(v => parseInt(v, 10))
            .filter(v => [1, 2, 3, 4, 5].includes(v))
            .map(v => String(v));

        if (parts.length) $('#title_multi').val(parts);
        syncTitleFromMulti();
        if (isEdit) return;
        $('#slotItemsServerContainer').hide();
        renderSelectedTitleSections();
        updateHiddenSectionInputs();
    })();
    toggleSlotItemsSection();

    // Add items dynamically
    $(document).on('click', '.addItemBtn', function(){
        const $currentRow = $(this).closest('.slot-item');
        const count = parseInt($currentRow.find('input[name="item_title_count[]"]').val(), 10) || 1;
        const $section = $currentRow.closest('.slot-title-section');

        let winVal = '';
        let ticketVal = '';
        if (count !== 3) {
            winVal = $section.find('.section-win-amount').first().val() || '';
            ticketVal = $section.find('.section-ticket-amount').first().val() || '';
        }

        const amountFieldsHtml = count === 3 ? `
            <div class="col-md-2">
                <label>Ticket Amount <span class="text-danger">*</span></label>
                <input type="text" name="ticket_amt[]" class="form-control" placeholder="Ticket Amount" required>
            </div>
            <div class="col-md-2">
                <label>First Price <span class="text-danger">*</span></label>
                <input type="text" name="first_price[]" class="form-control" placeholder="First Price" required>
            </div>
            <div class="col-md-2">
                <label>Second Price <span class="text-danger">*</span></label>
                <input type="text" name="second_price[]" class="form-control" placeholder="Second Price" required>
            </div>
            <div class="col-md-2">
                <label>Third Price <span class="text-danger">*</span></label>
                <input type="text" name="third_price[]" class="form-control" placeholder="Third Price" required>
            </div>` : `
            <input type="hidden" name="win_amount[]" class="row-win-amount" value="${String(winVal).replace(/"/g, '&quot;')}">
            <input type="hidden" name="ticket_amt[]" class="row-ticket-amount" value="${String(ticketVal).replace(/"/g, '&quot;')}">
            <input type="hidden" name="first_price[]" value="">
            <input type="hidden" name="second_price[]" value="">
            <input type="hidden" name="third_price[]" value="">`;

        const newRowHtml = `
            <div class="slot-item row mb-4">
                ${amountFieldsHtml}
                <div class="col-md-2">
                    <label>Group Name <span class="text-danger">*</span></label>
                    <input type="hidden" name="slot_item_id[]" value="">
                    <input type="hidden" name="item_title_count[]" value="${count}">
                    <input type="text" name="group_name[]" class="form-control" placeholder="Group Name" required>
                </div>
                <div class="col-md-2">
                    <label>Digit <span class="text-danger">*</span></label>
                    <input type="number" name="digit[]" class="form-control" placeholder="Digit" maxlength="${count}" required>
                </div>
                <div class="col-md-2">
                    <label>Color</label>
                    <input type="color" name="color[]" class="form-control" value="#000000">
                </div>
                <div class="col-md-2 d-flex align-items-start pt-4">
                    <button type="button" class="btn btn-success addItemBtn me-2">+</button>
                    <button type="button" class="btn btn-danger removeItemBtn" data-item-ids='[]'>Remove</button>
                </div>
            </div>`;

        $currentRow.after(newRowHtml);
    });

    //  Removeitems
    $(document).on('click', '.removeItemBtn', function(){

    const row = $(this).closest('.slot-item');

    const itemIds = $(this).data('item-ids');

    // Confirmation Popup
    if(!confirm('Are you sure you want to remove this item?')){
        return;
    }

    if(itemIds && Array.isArray(itemIds) && itemIds.length){

        const requests = itemIds.map(function (id) {
            return $.ajax({

                url: "{{ route('admin.slots.items.delete', ':id') }}"
                        .replace(':id', id),

                type: "POST",

                data: {
                    _token: "{{ csrf_token() }}"
                }

            });
        });

        $.when.apply($, requests).done(function () {
            row.remove();
            location.reload();
        }).fail(function () {
            alert('Failed to remove item. Please try again.');
        });

        return;
    }

    row.remove();

    });


    // Submit Form
    $('#slotForm').submit(function(e){

        e.preventDefault();
        clearValidationErrors();
        syncSectionHiddenAmounts();

        $.ajax({

            url: $('#slotForm').attr('action'),
            type: "POST",
            data: $(this).serialize(),

            success: function(response){

                if(response.status == true){



                    $('#slotForm')[0].reset();

                    window.location.href = "{{ route('admin.slots.index') }}";
                }
            },

            error: function(xhr){

                const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : null;
                if (!errors) {
                    alert('Something went wrong. Please try again.');
                    return;
                }

                let firstErrorInput = null;

                $.each(errors, function(key, value){
                    const message = Array.isArray(value) ? value[0] : value;
                    showFieldError(key, message);

                    if (!firstErrorInput) {
                        // Try to focus the first invalid field
                        const parts = String(key).split('.');
                        const base = parts[0];
                        const index = (parts.length > 1 && parts[1] !== '') ? parseInt(parts[1], 10) : null;

                        let $input = $('#slotForm').find(`[name="${base}"]`);
                        if (!$input.length) {
                            if (Number.isInteger(index) && index >= 0) {
                                $input = $('#slotForm').find(`[name="${base}[${index}]"]`);
                            }
                        }
                        if (!$input.length) {
                            const arrayName = `${base}[]`;
                            const $inputs = $('#slotForm').find(`[name="${arrayName}"]`);
                            if ($inputs.length) {
                                $input = (Number.isInteger(index) && index >= 0) ? $inputs.eq(index) : $inputs.first();
                            }
                        }
                        if ($input.length) firstErrorInput = $input;
                    }
                });

                if (firstErrorInput) {
                    firstErrorInput.trigger('focus');
                }
            }

        });

    });

});

</script>
@endsection
