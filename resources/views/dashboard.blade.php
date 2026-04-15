<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Tracker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; padding: 20px; }
        .card { border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: none; }
        .table thead { background-color: #007bff; color: white; }
        .device-col { white-space: normal !important; word-break: break-word; min-width: 250px; font-size: 0.9rem; }
        .isp-col { min-width: 150px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="card p-4">
        <h3 class="fw-bold mb-4 text-primary">Visitor Tracker Dashboard</h3>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="IP, Location અથવા ISP શોધો..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <form action="{{ route('clicks.deleteall') }}" method="POST" onsubmit="return confirm('શું તમે બધો જ ડેટા ડિલીટ કરવા માંગો છો?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete All Data</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Google Map</th> <th class="isp-col">Network (ISP)</th> 
                        <th class="device-col">Device Info</th>
                        <th>Time (IST)</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td><strong>{{ $row->ip }}</strong></td>
                        <td>📍 {{ $row->city }}, {{ $row->country }}</td>
                        
                        <td>
                            @if($row->latitude && $row->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success" 
                                   style="font-size: 0.8rem;">
                                   📍 View Map
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td class="isp-col"><span class="badge bg-info text-dark">{{ $row->isp ?? 'Unknown' }}</span></td> 
                        <td class="device-col">{{ $row->device }}</td>
                        <td style="white-space: nowrap;">
                            {{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                        </td>
                        <td class="text-center">
                            <form action="{{ route('click.delete', $row->id) }}" method="POST" onsubmit="return confirm('આ રેકોર્ડ ડિલીટ કરવો છે?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
