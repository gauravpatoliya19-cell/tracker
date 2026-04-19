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
        // ૧. સાચો Public IP મેળવો
        $ip = $request->ip();

        // ૨. IP-બેઝ લોકેશન મેળવો (City/Country માટે)
        $locationData = Location::get($ip);

        // ૩. ISP મેળવવા માટે API કોલ (Render પર આ જરૂરી છે)
        $ispName = 'Unknown';
        try {
            // timeout(3) રાખવો જરૂરી છે જેથી API મોડું કરે તો પ્રોજેક્ટ હેંગ ન થાય
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=isp");
            if ($response->successful()) {
                $ispName = $response->json('isp', 'Unknown');
            }
        } catch (\Exception $e) {
            $ispName = 'Unknown';
        }

        // ૪. ડેટાબેઝમાં એન્ટ્રી કરો અને ID મેળવો 
        // (insertGetId વાપરવું ફરજિયાત છે જેથી GPS ડેટા આ જ રો માં સેવ થાય)
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

        // ૫. લોડિંગ પેજ બતાવો અને ID પાસ કરો
        return view('loading', ['id' => $id]);
    }

    // ૬. GPS ડેટા અપડેટ કરવા માટેની મેથડ (AJAX દ્વારા કોલ થશે)
    public function updateLocation(Request $request)
    {
        // વેલિડેશન: ID અને લોકેશન હોવા જરૂરી છે
        if ($request->has(['id', 'latitude', 'longitude'])) {
            DB::table('clicks')
                ->where('id', $request->id)
                ->update([
                    'latitude'   => $request->latitude,
                    'longitude'  => $request->longitude,
                    'updated_at' => now(),
                ]);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
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

        // ડેટા વધુ હોય તો get() ને બદલે paginate(15) વાપરી શકાય
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
        DB::table('clicks')->truncate(); // truncate બધો જ ડેટા ક્લીન કરી દેશે અને ID ૧ થી શરૂ થશે
        return back()->with('success', 'બધો જ ડેટા ડિલીટ થઈ ગયો!');
    }
}
