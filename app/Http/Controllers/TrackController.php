<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
  public function track(Request $request)
{
    // 1. Get the IP Address
    $ip = $request->header('X-Forwarded-For')
        ? explode(',', $request->header('X-Forwarded-For'))[0]
        : $request->ip();

    // 2. Get Location Data (City, Country વગેરે માટે)
    $locationData = Location::get(trim($ip));

    // 3. ISP મેળવવા માટે નવો લોજિક (આનાથી Unknown નહીં આવે)
    $ispName = 'Unknown';
    try {
        $response = @file_get_contents("http://ip-api.com/json/" . trim($ip) . "?fields=isp");
        $json = json_decode($response);
        if ($json && isset($json->isp)) {
            $ispName = $json->isp;
        }
    } catch (\Exception $e) {
        $ispName = 'Unknown';
    }

    // 4. Insert into Database
    DB::table('clicks')->insert([
        'ip'         => trim($ip),
        'device'     => $request->header('User-Agent'),
        'isp'        => $ispName, // હવે અહીં સાચું નેટવર્ક નામ આવશે
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
