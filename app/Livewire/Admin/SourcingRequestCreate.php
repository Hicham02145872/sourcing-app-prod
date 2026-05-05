<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class SourcingRequestCreate extends Component
{
    use WithFileUploads;

    // Form data
    public $client_type = 'existing';

    public $user_id = '';

    public $client_name = '';

    public $client_email = '';

    public $client_phone = '';

    public $product_name = '';

    public $product_url = '';

    public $product_image;

    public $category_id = '';

    public $note = '';

    public $shipping_method = 'sea';

    public $sourcing_location = 'china';

    public $destinations = [];

    public $assigned_to_admin_id;

    // Searchable clients
    public $search = '';

    public $showDropdown = false;

    public function mount()
    {
        $this->addDestination();
        $this->assigned_to_admin_id = auth()->id();
    }

    public function addDestination()
    {
        $this->destinations[] = [
            'country_id' => '',
            'service_id' => '',
            'quantity' => 100,
        ];
    }

    public function removeDestination($index)
    {
        unset($this->destinations[$index]);
        $this->destinations = array_values($this->destinations);

        if (empty($this->destinations)) {
            $this->addDestination();
        }
    }

    public function selectClient($id)
    {
        $client = User::find($id);
        if ($client) {
            $this->user_id = $client->id;
            $this->search = $client->name.' ('.$client->email.')';
            $this->showDropdown = false;
        }
    }

    public function getClientsProperty()
    {
        if (empty($this->search) || $this->user_id) {
            return [];
        }

        return rescue(
            fn () => User::where('role', 'client')
                ->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                })
                ->limit(5)
                ->get()
                ->all(),
            []
        );
    }

    public function updatedSearch()
    {
        $this->user_id = '';
        $this->showDropdown = true;
    }

    #[On('trigger-save')]
    public function save()
    {
        $rules = [
            'client_type' => 'required|in:existing,new',
            'user_id' => 'required_if:client_type,existing|nullable|exists:users,id',
            'client_name' => 'required_if:client_type,new|nullable|string|max:255',
            'client_email' => 'required_if:client_type,new|nullable|email|max:255|unique:users,email',
            'product_name' => 'required|string|max:255',
            'product_url' => 'nullable|url|max:255',
            'product_image' => 'nullable|image|max:5120',
            'category_id' => 'required|exists:categories,id',
            'sourcing_location' => 'required|in:china,dubai',
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
            'assigned_to_admin_id' => 'required|exists:users,id',
        ];

        $this->validate($rules);

        // 1. Identify/Create User
        if ($this->client_type === 'new') {
            $tempPassword = Str::random(10);
            $user = User::create([
                'name' => $this->client_name,
                'email' => $this->client_email,
                'phone' => $this->client_phone,
                'password' => Hash::make($tempPassword),
                'role' => 'client',
                'email_verified_at' => now(), // Auto-verify
            ]);

            // Send credentials to the user
            $user->notify(new \App\Notifications\ClientAccountCreated($tempPassword));
        } else {
            $user = User::findOrFail($this->user_id);
        }

        // 2. Handle Image
        $imagePath = null;
        if ($this->product_image) {
            $imagePath = $this->product_image->store('sourcing-requests', 'public');
        }

        // 3. Create Sourcing Request
        $sourcingRequest = SourcingRequest::create([
            'user_id' => $user->id,
            'product_name' => $this->product_name,
            'product_url' => $this->product_url,
            'product_image' => $imagePath,
            'category_id' => $this->category_id,
            'note' => $this->note,
            'shipping_method' => $this->shipping_method,
            'sourcing_location' => $this->sourcing_location,
            'status' => 'pending',
            'assigned_to_admin_id' => $this->assigned_to_admin_id,
        ]);

        // 4. Create Destinations
        foreach ($this->destinations as $dest) {
            $sourcingRequest->destinations()->create([
                'country_id' => $dest['country_id'],
                'service_id' => $dest['service_id'],
                'quantity' => $dest['quantity'],
            ]);
        }

        session()->flash('success', 'Demande de sourcing créée avec succès.');

        return redirect()->route('admin.sourcing-requests.show', $sourcingRequest);
    }

    public function render()
    {
        return view('livewire.admin.sourcing-request-create', [
            'categories' => rescue(static fn () => Category::orderBy('name')->get(), collect()),
            'countries' => rescue(static fn () => Country::orderBy('name')->get(), collect()),
            'services' => rescue(static fn () => Service::orderBy('name')->get(), collect()),
            'admins' => rescue(static fn () => User::whereIn('role', ['admin', 'super-admin'])->orderBy('name')->get(), collect()),
        ]);
    }
}
