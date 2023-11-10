<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;

class OutgoingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function updateToken(Request $request)
    {
        // dd($request);
        try {
            $request->user()->update(['fcm_token' => $request->token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required'
        ]);

        try {
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */

            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));

            /* or */

            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->sendMessage($fcmTokens);

            return redirect()->back()->with('success', 'Notification Sent Successfully!!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Something goes wrong while sending notification.');
        }
    }
    public function index()
    {
        if (request()->ajax()) {
            $query = OutgoingLetter::where('from', auth()->user()->email)->get();
            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                    <center>
                        <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('outgoing.show', base64_encode($item->reference_number)) . '">
                            Show Detail
                        </a></center>';
                })
                ->addColumn('status', function ($item) {
                    return '<a href="#" class="font-semibold text-gray-900 underline dark:text-white decoration-blue-500">' . $item->status . '<a>';
                })
                ->rawColumns(['action', 'status'])
                ->make();
        }
        return view('pages.dashboard.outgoing.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.outgoing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type');
        $akademik = AppConfig::where('position', 'AKADEMIK')->with('users')->first();
        $referenceNumber = $this->getReferenceNumber($type);
        $user = auth()->user();
        if ($request->hasFile('file_path')) {
            $this->validate($request, [
                'file_path' => 'file|mimes:pdf',
            ]);
        }
        DB::beginTransaction();
        try {
            $outgoing = OutgoingLetter::create([
                'reference_number' => $referenceNumber,
                'subject' => $request->subject,
                'from' => $user->email,
                'to' => $akademik->users->email,
                'note' => $request->note,
                'type' => $request->type,
                'letter_date' => now(),
                'submit_date' => now(),
                'identifier' => auth()->user()->identifier,
                'status' => getStatus('SENT'),
            ]);
            if ($request->hasFile('file_path')) {
                $outgoing->file_path = $request->file_path->store('file', 'public');
            }
            $outgoing->save();

            IncomingLetter::create([
                'reference_number' => $referenceNumber,
                'subject' => $request->subject,
                'from' => $user->email,
                'to' => $akademik->users->email,
                'note' => $request->note,
                'type' => $request->type,
                'submit_date' => now(),
                'status' => 'Permintaan Baru'
            ]);
            DB::commit();

            return redirect()->route('outgoing.index')->banner('Permintaan Surat telah dikirimkan');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->dangerBanner('Gagal menyimpan surat: ' . $e->getMessage());
        }
    }


    public function getReferenceNumber($type)
    {
        $currentYear = date('Y');
        $lastSurat = OutgoingLetter::where('type', $type)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->first();
        if (!$lastSurat) {
            $lastId = 1;
        } else {
            $lastId = $lastSurat->id + 1;
        }

        $typeAbbreviation = '';
        $words = explode(' ', $type);
        foreach ($words as $word) {
            $typeAbbreviation .= strtoupper(substr($word, 0, 1));
        }
        $month = date('m');
        $referenceNumber = "STMI-$month-$typeAbbreviation-$currentYear-$lastId";

        return $referenceNumber;
    }

    public function showpdf($reference_number)
    {
        $reference_number = base64_decode($reference_number);
        $document = DB::table('outgoing_letters')
            ->where('reference_number', $reference_number)
            ->first();
        $path = $document->file_path;
        if ($path) {
            $content = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
            $url = url($path) . '#toolbar=0';
            return response()->make($content, 200, [
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return view('errors.404');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($ref_number)
    {
        $ref_number = base64_decode($ref_number);

        $data = OutgoingLetter::where('reference_number', $ref_number)->first();
        return view('pages.dashboard.outgoing.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
