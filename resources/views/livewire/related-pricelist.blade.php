<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelatedprice === false) $set('showrelatedprice', true) @else $set('showrelatedprice', false) @endif">
				{{ __("Price List ") }}({{ $item->product_prices->count() }})
			</button>
			<button wire:click.prevent="addrelated()" class="accordion__upload">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>
		{{-- add related specs --}}
		@if ($addrelatedprice)
			<div class="modal" id="modalelements" style="display: block">
				<div class="modal-content modal--tabel">
					{{-- Header of the table --}}
					<div class="panel__header">
						<h1 class="panel__header--title">
							{{ __("Add related Pricelist") }}
						</h1>
						<input class="panel__header--input panel__header--checked" wire:click.prevent="saveitems()" type="button" value="Save">
					</div>
					{{-- Table --}}
					<div style="overflow-y: auto; position: relative; background: white; height: 100%;">
						<table class="table table-top">
							<thead>
								<tr>
									<th class="wid-1"><button class="table__header--btn">Product</button></th>
									<th class="wid-3"><button class="table__header--btn">Pricelist</button></th>
									<th class="wid-3"><button class="table__header--btn">Value</button></th>
									<th class="wid-1"><button class="table__header--btn">TVA</button></th>
									<th class="wid-1"></th>
								</tr>
							</thead>
						</table>
						<table class="table" style="margin-top: 2rem">
							<tbody>
								@foreach ($priceAndValues as $index => $priceAndValue)
									<tr wire:key="price-row-{{ $index }}">
										<td class="wid-1" data-title="Name">
											{{ $item->name }}
										</td>
										<td class="wid-3" data-title="Specification">
											@if ($priceAndValue["allow"])
												<div class="table__drop" style="position: relative">
													<input class="table__drop--input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
													<ul class="table__drop--list">
														@if (count($addprices) >= 1)
															@foreach ($addprices as $pri)
																<li class="table__drop--item" wire:click.prevent="selectitem({{ $index }}, {{ $pri->id }}, '{{ $pri->name }}')">
																	{{ $pri->name }} ({{ $pri->currency->name }})
																</li>
															@endforeach
														@else
															<li>{{ __("No pricelist found") }}</li>
														@endif
													</ul>
													<svg wire:click.prevent="denny({{ $index }})" style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
														<line x1="18" y1="6" x2="6" y2="18"></line>
														<line x1="6" y1="6" x2="18" y2="18"></line>
													</svg>
												</div>
											@else
												<div style="position: relative" class="table__drop--input">
													@if ($priceAndValue["itemselected"])
														{{ $priceAndValue["itemselected"] }}
													@else
														{{ __("Select a item") }}
													@endif
													<svg wire:click.prevent="allowselect({{ $index }})" style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
														class="feather feather-edit-2">
														<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
														</path>
													</svg>
												</div>
											@endif
											<input type="hidden" wire:model.defer="priceAndValues.{{ $index }}.price.name">
										</td>
										<td class="wid-3" data-title="Price">
											<input type="text" required class="table__drop--input" wire:model.defer="priceAndValues.{{ $index }}.price.value">
										</td>
										<td class="wid-1" data-title="TVA">
											<input type="text" required class="table__drop--input" wire:model.defer="priceAndValues.{{ $index }}.price.tva" value="{{ old("priceAndValues." . $index . ".price.tva", 19) }}">
										</td>

										<td class="wid-1" data-title="Action">
											<div class="table__buttons">
												@if ($index == $row - 1)
													<button type="button" class="edit" wire:click="plus">
														<svg>
															<line x1="12" y1="5" x2="12" y2="19">
															</line>
															<line x1="5" y1="12" x2="19" y2="12">
															</line>
														</svg>
													</button>
													<button type="button" class="save" wire:click="clear({{ $index }})">
														<svg>
															<line x1="18" y1="6" x2="6" y2="18">
															</line>
															<line x1="6" y1="6" x2="18" y2="18">
															</line>
														</svg>
													</button>
												@endif
												@if ($index != $row - 1)
													<button type="button" class="save" wire:click="clear({{ $index }})">
														<svg>
															<line x1="18" y1="6" x2="6" y2="18">
															</line>
															<line x1="6" y1="6" x2="18" y2="18">
															</line>
														</svg>
													</button>
												@endif
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<span class="top-up-modal delete" wire:click="closemodal">
						<svg>
							<line x1="18" y1="6" x2="6" y2="18">
							</line>
							<line x1="6" y1="6" x2="18" y2="18">
							</line>
						</svg>
					</span>
					<a href="#top1" class="top-up-modal" id="topUp">
						<svg>
							<polyline points="18 15 12 9 6 15"></polyline>
						</svg>
					</a>
				</div>
			</div>
		@endif
		@if ($editmultiple)
			<div class="modal" id="modalelements" style="display: block">
				<div class="modal-content modal--tabel">
					{{-- Header of the table --}}
					<div class="panel__header">
						<h1 class="panel__header--title">
							{{ __("Edit multiple related Prices") }}
						</h1>
						<input class="panel__header--input panel__header--checked" wire:click.prevent="confirmpricemultiple()" type="button" value="Save">
					</div>
					{{-- Table --}}
					<div style="overflow-y: auto; position: relative; background: white; height: 100%">
						<table class="table table-top">
							<thead>
								<tr>
									<th class="wid-2"><button class="table__header--btn">Product</button></th>
									<th class="wid-2"><button class="table__header--btn">Pricelist</button></th>
									<th class="wid-1"><button class="table__header--btn">Value</button></th>
								</tr>
							</thead>
						</table>
						<table class="table" style="margin-top: 2rem">
							<tbody>
								@foreach ($priceAndValues as $index => $priceAndValue)
									<tr wire:key="price-row-{{ $index }}">
										<td class="wid-2" data-title="Name">
											{{ $item->name }}
										</td>
										<td class="wid-2" data-title="Pricelist">
											@if ($priceAndValue["allow"])
												<div class="table__drop" style="position: relative">
													<input class="table__drop--input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
													<ul class="table__drop--list">
														@if (count($addprices) >= 1)
															@foreach ($addprices as $pri)
																<li class="table__drop--item" wire:click.prevent="selectitem({{ $index }}, {{ $pri->id }}, '{{ $pri->name }}')">
																	{{ $pri->name }} ({{ $pri->currency->um }})
																</li>
															@endforeach
														@else
															<li>{{ __("No pricelist found") }}</li>
														@endif
													</ul>
													<svg wire:click.prevent="denny({{ $index }})" style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
														class="feather feather-x">
														<line x1="18" y1="6" x2="6" y2="18"></line>
														<line x1="6" y1="6" x2="18" y2="18"></line>
													</svg>
												</div>
											@else
												<div style="position: relative" class="table__drop--input">
													@if ($priceAndValue["itemselected"])
														{{ $priceAndValue["itemselected"] }}
													@else
														{{ __("Select a item") }}
													@endif
													<svg wire:click.prevent="allowselect({{ $index }})" style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
														class="feather feather-edit-2">
														<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
														</path>
													</svg>
												</div>
											@endif
											<input type="hidden" wire:model.defer="priceAndValues.{{ $index }}.price.name">
										</td>
										<td class="wid-1" data-title="Value">
											<input type="text" required class="table__drop--input" wire:model.defer="priceAndValues.{{ $index }}.price.value">
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<span class="top-up-modal delete" wire:click="closemodal">
						<svg>
							<line x1="18" y1="6" x2="6" y2="18">
							</line>
							<line x1="6" y1="6" x2="18" y2="18">
							</line>
						</svg>
					</span>
					<a href="#top1" class="top-up-modal" id="topUp">
						<svg>
							<polyline points="18 15 12 9 6 15"></polyline>
						</svg>
					</a>
				</div>
			</div>
		@endif
		{{-- end add related specs --}}
		@if ($showrelatedprice)
			<div class="accordion__content">
				<div>
					@if ($item->product_prices->count() > 0)
						{{-- delete single record --}}
						<div class="modal" id="confirmationmodalprice">
							<div class="modal-content">
								<h1 class="modal-content-title">
									{{ __("Are you sure to delete this record?") }}
								</h1>
								<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
								<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalprice').style.display='none'" value="Cancel">
								<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalprice').style.display='none'">
									<svg>
										<line x1="18" y1="6" x2="6" y2="18">
										</line>
										<line x1="6" y1="6" x2="18" y2="18">
										</line>
									</svg>
								</span>
							</div>
						</div>
						{{-- delete myltiple records --}}
						<div class="modal" id="confirmationmodalmultiple">
							<div class="modal-content">
								<h1 class="modal-content-title">
									{{ __("Are you sure to delete those records?") }}
								</h1>
								<input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
								<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">
								<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">
									<svg>
										<line x1="18" y1="6" x2="6" y2="18">
										</line>
										<line x1="6" y1="6" x2="18" y2="18">
										</line>
									</svg>
								</span>
							</div>
						</div>
						{{-- Header of the table --}}
						<div class="panel__header">
							<input class="panel__header--input" type="text" wire:model.debounce.300ms="search" placeholder="Search..." style="grid-column: 1/4">
							<div class="panel__header--bundle">
								<div class="dropdown">
									<button wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif" class="dropdown-button">
										Columns
									</button>
									@if ($col)
										<div class="dropdown-list" style="display: flex;">
											@foreach ($columns as $column)
												<div class="dropdown-item">
													<input type="checkbox" wire:model="selectedColumns" value="{{ $column }}" {{ in_array($column, $selectedColumns) ? "checked" : "" }}>
													<label>{{ $column }}</label>
												</div>
											@endforeach
										</div>
									@endif
								</div>
								<div class="dropdown none" @if ($checked) style="display: unset; z-index: 5;" @endif>
									<button wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif" class="dropdown-button none" @if ($checked) style="display: flex" @endif>
										With Checked({{ count($checked) }})
									</button>
									@if ($checked && $all)
										<div class="dropdown-list" style="display: flex;">
											<button class="dropdown-item delete" type="button" wire:click="confirmRemovalmultiple()">
												Delete
											</button>
											<button class="dropdown-item submit" type="button" wire:click="editSelected">
												Edit
											</button>
										</div>
									@endif
								</div>
							</div>
							@if ($selectPage && $selectAll)
								<div class="panel__header--checked">
									<p>
										You selected <strong>{{ count($checked) }}</strong> items.
									</p>
								</div>
							@elseif($selectPage)
								<div class="panel__header--checked" wire:click="selectAll">
									<p>
										You selected {{ count($checked) }} items, select all?
									</p>
								</div>
							@endif
						</div>
						{{-- Table --}}
						<table class="table">
							<thead>
								<tr>
									<th><input type="checkbox" wire:model="selectPage"></th>
									@if ($this->showColumn("Id"))
										<th wire:click="sortBy('id')">
											<button class="table__header--btn">
												ID
											</button>
										</th>
									@endif
									@if ($this->showColumn("Name"))
										<th wire:click="sortBy('name')">
											<button class="table__header--btn">
												Name
											</button>
										</th>
									@endif
									@if ($this->showColumn("Currency"))
										<th>
											<button class="table__header--btn">
												Currency
											</button>
										</th>
									@endif
									@if ($this->showColumn("Value"))
										<th>
											<button class="table__header--btn">
												Value
											</button>
										</th>
									@endif
									@if ($this->showColumn("TVA"))
										<th>
											<button class="table__header--btn">
												TVA
											</button>
										</th>
									@endif
									@if ($this->showColumn("Created At"))
										<th wire:click="sortBy('created_at')">
											<button class="table__header--btn">
												Created at
											</button>
										</th>
									@endif
									<th></th>
								</tr>
							</thead>
							<tbody>
								@if ($relatedprices->isEmpty())
									<tr>
										<td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
											found.</td>
									</tr>
								@else
									@foreach ($relatedprices as $index => $prices)
										@if ($index >= $perPage)
											<?php
											break;
											?>
										@endif
										<tr class="@if ($this->isChecked($prices->id)) table__row--selected @endif">
											<td data-title="Check">
												<input type="checkbox" value="{{ $prices->id }}" wire:model="checked">
											</td>
											@if ($this->showColumn("Id"))
												<td data-title="ID">
													{{ $prices->id }}
												</td>
											@endif
											@if ($this->showColumn("Name"))
												<td data-title="Name">
													@if ($editedrow !== $index)
														<a href="{{ route("show_pricelist", ['id'=> $prices->pricelist->id ])}}">
															{{ $prices->pricelist->name }}
														</a>
													@else
														@if ($allow)
															<div class="table__drop" style="position: relative">
																<input class="table__drop--input" wire:model.debounce.300ms="searchadd" placeholder="Search.." type="text">
																<ul class="table__drop--list">
																	@if ($addprices->isEmpty())
																		<li class="table__drop--item">No
																			record
																			found.</li>
																	@else
																		@foreach ($addprices as $pri)
																			<li class="table__drop--item" wire:click.prevent="select({{ $pri->id }})">
																				{{ $pri->name }}
																			</li>
																		@endforeach
																	@endif
																</ul>
																<svg wire:click.prevent="dennyselect()" style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
																	<line x1="18" y1="6" x2="6" y2="18"></line>
																	<line x1="6" y1="6" x2="18" y2="18"></line>
																</svg>
															</div>
														@else
															<button wire:click.prevent="allow" class="table__drop--input">
																{{ $itemselected->name }}
															</button>
														@endif
													@endif
												</td>
											@endif
											@if ($this->showColumn("Currency"))
												<td data-title="Currency">
													{{ $prices->pricelist->currency->name }}
												</td>
											@endif
											@if ($this->showColumn("Value"))
												<td>
													@if ($editedrow !== $index)
														{{ $prices->value }}
													@else
														<input type="text" required class="table__edit" wire:model="pricelist.{{ $index }}.value">
													@endif
												</td>
											@endif
											@if ($this->showColumn("TVA"))
												<td>
													{{ $prices->tva_percent }}
												</td>
											@endif
											@if ($this->showColumn("Created At"))
												<td data-title="Created At">
													<div class="table__time">
														<svg>
															<circle cx="12" cy="12" r="10">
															</circle>
															<polyline points="12 6 12 12 16 14"></polyline>
														</svg>
														{{ $prices->pricelist->created_at }}
													</div>
												</td>
											@endif
											<td data-title="Action">
												<div class="table__buttons">
													@if ($editedrow !== $index)
														<button class="edit" wire:click.prevent="edititem({{ $prices->id }}, {{ $prices->pricelist->id }}, {{ $index }})">
															<svg>
																<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
																</path>
															</svg>
														</button>
														<button class="delete" wire:click.prevent="confirmRemoval({{ $prices->id }})">
															<svg>
																<polyline points="3 6 5 6 21 6"></polyline>
																<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
																</path>
															</svg>
														</button>
													@else
														<button class="edit" wire:click.prevent="confirmitem({{ $index }},{{ $prices->id }})">
															<svg>
																<polyline points="20 6 9 17 4 12"></polyline>
															</svg>
														</button>
														<button class="save" wire:click.prevent="canceledit()">
															<svg>
																<line x1="18" y1="6" x2="6" y2="18">
																</line>
																<line x1="6" y1="6" x2="18" y2="18">
																</line>
															</svg>
														</button>
													@endif
												</div>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
						@if ($perPage <= count($relatedprices))
							<div class="table__load-more" wire:click="load">
								Load more
							</div>
						@endif
					@else
						<p class="mt-2">No records related</p>
					@endif
				</div>
			</div>
		@endif
	</div>
</div>
