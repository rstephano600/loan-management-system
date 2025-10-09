<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\ClientLoanPhoto;
use App\Models\Client;
use App\Models\ClientLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientLoanPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = ClientLoanPhoto::with(['client', 'loan', 'creator'])->latest()->paginate(10);
        return view('in.loans.client_loan_photos.index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $loans = ClientLoan::all();
        return view('in.loans.client_loan_photos.create', compact('clients', 'loans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_loan_id' => 'nullable|exists:client_loans,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'date_captured' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('client_loan_photos', 'public');
            $validated['photo'] = $path;
        }

        $validated['created_by'] = Auth::id();

        ClientLoanPhoto::create($validated);

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientLoanPhoto $clientLoanPhoto)
    {
        return view('in.loans.client_loan_photos.show', compact('clientLoanPhoto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientLoanPhoto $clientLoanPhoto)
    {
        $clients = Client::all();
        $loans = ClientLoan::all();
        return view('in.loans.client_loan_photos.edit', compact('clientLoanPhoto', 'clients', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientLoanPhoto $clientLoanPhoto)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_loan_id' => 'nullable|exists:client_loans,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'date_captured' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle new file upload
        if ($request->hasFile('photo')) {
            // delete old photo
            if ($clientLoanPhoto->photo && Storage::disk('public')->exists($clientLoanPhoto->photo)) {
                Storage::disk('public')->delete($clientLoanPhoto->photo);
            }
            $path = $request->file('photo')->store('client_loan_photos', 'public');
            $validated['photo'] = $path;
        }

        $validated['updated_by'] = Auth::id();

        $clientLoanPhoto->update($validated);

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientLoanPhoto $clientLoanPhoto)
    {
        if ($clientLoanPhoto->photo && Storage::disk('public')->exists($clientLoanPhoto->photo)) {
            Storage::disk('public')->delete($clientLoanPhoto->photo);
        }

        $clientLoanPhoto->delete();

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo deleted successfully.');
    }
}
