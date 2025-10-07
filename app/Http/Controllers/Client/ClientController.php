<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // 🔍 Search by name, email, phone, or business name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 🎯 Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 🏦 Filter by assigned loan officer
        if ($officer = $request->input('loan_officer')) {
            $query->where('assigned_loan_officer_id', $officer);
        }

        // ✅ Filter by KYC completion
        if ($kyc = $request->input('kyc_completed')) {
            $query->where('kyc_completed', $kyc);
        }

        // Pagination
        $clients = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $loanOfficers = User::where('role', 'loanofficer')->get();

        return view('in.clients.index', compact('clients', 'loanOfficers'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $loanOfficers = User::where('role', 'loanofficer')->get();
        return view('in.clients.create', compact('loanOfficers'));
    }

    /**
     * Store a newly created client in storage.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'client_type' => 'required|string|max:255',
        'business_name' => 'nullable|string|max:255',
        'business_registration_number' => 'nullable|string|max:255',
        'tax_identification_number' => 'nullable|string|max:255',
        'industry_sector' => 'nullable|string|max:255',
        'years_in_business' => 'nullable|integer|min:0',
        'number_of_employees' => 'nullable|integer|min:0',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email',
        'phone' => 'required|string|max:20|unique:clients,phone',
        'alternative_phone' => 'nullable|string|max:20',
        'address_line1' => 'nullable|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'state_province' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'credit_score' => 'nullable|numeric|min:0|max:1000',
        'credit_rating' => 'nullable|string|max:50',
        'risk_category' => 'nullable|string|max:50',
        'status' => 'required|string|max:50',
        'blacklist_reason' => 'nullable|string|max:255',
        'assigned_loan_officer_id' => 'nullable|exists:users,id',
        'kyc_completed' => 'boolean',
    ]);

    $validated['kyc_completed_at'] = $request->kyc_completed ? now() : null;

    Client::create($validated);

    return redirect()->route('clients.index')->with('success', 'Client created successfully.');
}


    /**
     * Display the specified client.
     */
    public function show($id)
    {
        $client = Client::with(['loanOfficer', 'documents', 'financialInfo'])->findOrFail($id);
        return view('in.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $loanOfficers = User::where('role', 'loanofficer')->get();
        return view('in.clients.edit', compact('client', 'loanOfficers'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_type' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'tax_identification_number' => 'nullable|string|max:255',
            'industry_sector' => 'nullable|string|max:255',
            'years_in_business' => 'nullable|integer|min:0',
            'number_of_employees' => 'nullable|integer|min:0',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id . ',id',
            'phone' => 'required|string|max:20|unique:clients,phone,' . $client->id . ',id',
            'alternative_phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'credit_score' => 'nullable|numeric|min:0|max:1000',
            'credit_rating' => 'nullable|string|max:50',
            'risk_category' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'blacklist_reason' => 'nullable|string|max:255',
            'assigned_loan_officer_id' => 'nullable|exists:users,id',
            'kyc_completed' => 'boolean',
        ]);

        $validated['kyc_completed_at'] = $request->kyc_completed ? now() : null;

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }


    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
