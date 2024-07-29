<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Offer;
use PDF;

class OfferController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id', 'asc')->get();
        return view('offer.index', compact('clients'));
    }

    public function show($client_id)
    {
        $client = Client::findOrFail($client_id);
        $offers = Offer::where('client_id', $client_id)->get();
        return view('offer.show', compact('client', 'offers'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'offers' => 'required|array',
            'offers.*.service' => 'required|string',
            'offers.*.service_price' => 'required|numeric',
            'offers.*.quantity' => 'required|integer',
        ]);

        foreach ($request->offers as $offerData) {
            Offer::create([
                'client_id' => $request->client_id,
                'service' => $offerData['service'],
                'service_price' => $offerData['service_price'],
                'quantity' => $offerData['quantity'],
                'total_price' => $offerData['total_price'],
            ]);
        }

        return response()->json(['success' => true]);
    }


    public function exportPdf($client_id)
    {
        $client = Client::find($client_id);
        $offers = Offer::where('client_id', $client_id)->get();

        $pdf = PDF::loadView('offer.pdf', compact('client', 'offers'))
                   ->setPaper('a4', 'portrait')
                   ->setOption('defaultFont', 'Amiri');

        $fileName = now()->format('Ymd') . '-00' . $client_id . '.pdf';

        return $pdf->download($fileName);
    }

    public function updatePaymentPolicy(Request $request, $client_id)
    {
        $request->validate([
            'payment_policy' => 'required|string',
        ]);

        $client = Client::findOrFail($client_id);
        $client->payment_policy = $request->payment_policy;
        $client->save();

        return response()->json(['success' => true]);
    }
}

