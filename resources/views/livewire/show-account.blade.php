<div>
	<x-alert />
	<x-loading />
	<div class="item__header">
		<h1 class="item__header-title" id="title">Account: {{ $account->name }}</h1>
		<div class="item__header-buttons">
			<a class="item__header-btn" href="{{ route("accounts") }}" data-tooltip-left="Back to all accounts">
				<svg>
					<polyline points="11 17 6 12 11 7"></polyline>
					<polyline points="18 17 13 12 18 7"></polyline>
				</svg>
			</a>
			@if ($edititem === null)
				<button class="item__header-btn" type="button" value="Edit" wire:click.prevent="edititem()" data-tooltip-center="Edit this account">
					<svg>
						<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
						<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
					</svg>
				</button>
			@else
				<button class="item__header-btn confirm" type="button" wire:click.prevent="saveitem()" value="Save" data-tooltip-center="Save this changes"><svg>
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg></button>
				<button class="item__header-btn" type="button" wire:click.prevent="canceledit()" value="Cancel" data-tooltip-center="Cancel this changes"><svg>
						<line x1="18" y1="6" x2="6" y2="18">
						</line>
						<line x1="6" y1="6" x2="18" y2="18">
						</line>
					</svg></button>
			@endif
			<button wire:click.prevent="confirmItemRemoval" class="item__header-btn delete" type="button" value="Delete" data-tooltip-right="Delete this account">
				<svg>
					<polyline points="3 6 5 6 21 6"></polyline>
					<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
				</svg>
			</button>
		</div>
	</div>
	<div class="modal" id="confirmationmodal">
		<div class="modal-content">
			<h1 class="modal-content-title">
				{{ __("Are you sure to delete this record?") }}
			</h1>
			<input wire:click.prevent="deleteRecord()" class="modal-content-btn submit" type="button" value="Confirm">
			<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">
			<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</span>
		</div>
	</div>
	<div class="tab">
		<div class="tabs">
			<h3 class="tabs__page active">Details</h3>
			<h3 class="tabs__page">Related</h3>
		</div>
		<div class="tab__list">
			<div class="tabs__content active" id="Details">
				<div class="item__form">
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->name }}</div>
							<label>Name</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="text" wire:model.defer="record.name" required>
							<label>Name</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->type }}</div>
							<label>Type</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="text" wire:model.defer="record.type" required>
							<label>Type: individual / juridic</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->first_name }}</div>
							<label>First Name </label>
						</div>
					@else
						<div class="item__form-input">
							<input type="text" wire:model.defer="record.first_name" required>
							<label>First Name</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->last_name }}</div>
							<label>Last Name</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="text" wire:model.defer="record.last_name" required>
							<label>Last Name</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->phone }}</div>
							<label>Phone</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="tel" wire:model.defer="record.phone" required>
							<label>Phone</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $account->email }}</div>
							<label>Email</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="email" wire:model.defer="record.email" required>
							<label>Email</label>
						</div>
					@endif
					@if ($account->type == "juridic")
						@if ($edititem === null)
							<div class="item__form-input-close">
								<div>{{ $account->company_name }}</div>
								<label>Company Name</label>
							</div>
						@else
							<div class="item__form-input">
								<input type="text" wire:model.defer="record.company_name" required>
								<label>Company Name</label>
							</div>
						@endif
						@if ($edititem === null)
							<div class="item__form-input-close">
								<div>{{ $account->registration_code }}</div>
								<label>Registration Code</label>
							</div>
						@else
							<div class="item__form-input">
								<input type="text" wire:model.defer="record.registration_code" required>
								<label>Registration Code</label>
							</div>
						@endif
						@if ($edititem === null)
							<div class="item__form-input-close">
								<div>{{ $account->registration_number }}</div>
								<label>Registration Number</label>
							</div>
						@else
							<div class="item__form-input">
								<input type="text" wire:model.defer="record.registration_number" required>
								<label>Registration Number</label>
							</div>
						@endif
						@if ($edititem === null)
							<div class="item__form-input-close">
								<div>{{ $account->bank_name }}</div>
								<label>Bank Name</label>
							</div>
						@else
							<div class="item__form-input">
								<input type="text" wire:model.defer="record.bank_name" required>
								<label>Bank Name</label>
							</div>
						@endif
						@if ($edititem === null)
							<div class="item__form-input-close">
								<div>{{ $account->account }}</div>
								<label>Bank Account</label>
							</div>
						@else
							<div class="item__form-input">
								<input type="text" wire:model.defer="record.account" required>
								<label>Bank Account</label>
							</div>
						@endif
					@endif
					<div class="item__form-input-close">
						<div>{{ $account->created_at }}</div>
						<label>Create date / time</label>
					</div>
					<div class="item__form-input-close">
						<div>{{ $account->updated_at }}</div>
						<label>Updated date / time</label>
					</div>
					@if ($edititem != null)
						<button class="item__form-btn item__form-long" wire:click.prevent="saveaccount()" value="Save">
							Save
						</button>
					@endif
				</div>
			</div>
			<div class="tabs__content">
				{{-- @livewire('related-account-orders', ['accountId' => $account->id], key('second' . $account->id)) --}}
				@livewire("related-addresses", ["account" => $account], key("first" . $account->id))
				@livewire("related-orders", ["account" => $account], key($account->id))
			</div>
		</div>
	</div>
</div>
