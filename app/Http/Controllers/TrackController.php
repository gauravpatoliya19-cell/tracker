<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
 public function track(Request $request)
{
    // ૧. સાચો IP મેળવો
    $ip = $request->header('X-Forwarded-For')
        ? explode(',', $request->header('X-Forwarded-For'))[0]
        : $request->ip();

    // ૨. સીટી અને કન્ટ્રી માટે લોકેશન ડેટા
    $locationData = Location::get(trim($ip));

    // ૩. ISP મેળવવા માટે ડાયરેક્ટ API કોલ (આનાથી Unknown નહીં આવે)
    $ispName = 'Unknown';
    try {
        // ip-api.com માંથી ISP ડેટા ખેંચવો
        $apiUrl = "http://ip-api.com/json/" . trim($ip) . "?fields=isp";
        $response = @file_get_contents($apiUrl);
        $json = json_decode($response);
        
        if ($json && isset($json->isp)) {
            $ispName = $json->isp;
        }
    } catch (\Exception $e) {
        $ispName = 'Unknown';
    }

    // ૪. ડેટાબેઝમાં એન્ટ્રી કરો
    DB::table('clicks')->insert([
        'ip'         => trim($ip),
        'device'     => $request->header('User-Agent'),
        'isp'        => $ispName, // અહીં હવે સાચું નામ આવશે
        'city'       => $locationData ? ($locationData->cityName ?? 'Unknown') : 'Unknown',
        'country'    => $locationData ? ($locationData->countryName ?? 'Unknown') : 'Unknown',
        'latitude'   => $locationData ? $locationData->latitude : null,
        'longitude'  => $locationData ? $locationData->longitude : null,
        'clicked_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('https://google.com');
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
        // Render પર truncate માં તકલીફ પડે તો delete() વાપરી શકાય
        DB::table('clicks')->delete(); 
        return back()->with('success', 'બધો જ ડેટા ડિલીટ થઈ ગયો!');
    }
}
