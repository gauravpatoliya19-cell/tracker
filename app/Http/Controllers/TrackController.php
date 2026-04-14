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

        // 2. Get Location Data
        $locationData = Location::get(trim($ip));

        // 3. Insert into Database (ISP કોલમ સાથે)
        DB::table('clicks')->insert([
            'ip'         => trim($ip),
            'device'     => $request->header('User-Agent'),
            'isp'        => $locationData ? $locationData->isp : 'Unknown', // આ લાઈન એડ કરી છે
            'city'       => $locationData ? $locationData->cityName : 'Unknown',
            'country'    => $locationData ? $locationData->countryName : 'Unknown',
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
                  ->orWhere('isp', 'LIKE', "%$search%"); // સર્ચમાં ISP પણ એડ કર્યું
            });
        }

        $data = $query->latest()->get();
        return view('dashboard', compact('data'));
    }

    public function destroy($id)
    {
        DB::table('clicks')->where('id', $id)->delete();
        return back()->with('success', 'રેકોર્ડ ડિલીટ થઈ ગયો!');
    }

    public function destroyAll()
    {
        DB::table('clicks')->truncate();
        return back()->with('success', 'બધો જ ડેટા ડિલીટ થઈ ગયો!');
    }
}