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
        $ip = $request->ip();

        // ૧. પહેલો પ્રયત્ન: Stevebauman Location
        $locationData = Location::get($ip);
        
        $city = $locationData->cityName ?? 'Unknown';
        $country = $locationData->countryName ?? 'Unknown';
        $lat = $locationData->latitude ?? null;
        $lon = $locationData->longitude ?? null;

        // ૨. બીજો પ્રયત્ન: જો પહેલું ફેલ જાય, તો ip-api.com વાપરો
        if ($city == 'Unknown' || $city == '') {
            try {
                $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");
                if ($response->successful()) {
                    $city = $response->json('city', 'Unknown');
                    $country = $response->json('country', 'Unknown');
                    $lat = $response->json('lat', $lat);
                    $lon = $response->json('lon', $lon);
                }
            } catch (\Exception $e) { }
        }

        // ડેટા ઇન્સર્ટ કરો
        $id = DB::table('clicks')->insertGetId([
            'ip'         => $ip,
            'city'       => $city,
            'country'    => $country,
            'latitude'   => $lat,
            'longitude'  => $lon,
            'device'     => $request->header('User-Agent'),
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
