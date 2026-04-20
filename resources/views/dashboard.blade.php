<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Tracker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #2e7d32;
            --bg-light: #f8f9fc;
        }

        body { 
            background-color: var(--bg-light); 
            padding: 30px 20px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card { 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            border: none; 
            overflow: hidden;
        }

        .dashboard-header {
            background: white;
            padding: 25px;
            border-bottom: 1px solid #edf2f7;
        }

        /* Table Styling */
        .table thead { 
            background-color: #f1f4f9; 
        }
        
        .table thead th {
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 15px;
            border: none;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f4f9;
        }

        .device-col { 
            max-width: 300px; 
            font-size: 0.8rem; 
            color: #718096; 
            line-height: 1.4;
        }

        /* Modern Location & Map UI */
        .location-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .city-text {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }

        .btn-map {
            background: #e8f5e9;
            color: var(--success-color);
            border: 1px solid #c8e6c9;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            width: fit-content;
            text-decoration: none;
        }

        .btn-map:hover {
            background: var(--success-color);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
            transform: translateY(-1px);
        }

        .no-gps {
            font-size: 0.7rem;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Badge Styling */
        .badge-isp {
            background-color: #ebf4ff;
            color: #2b6cb0;
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #4a5568;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="card">
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h3 class="fw-bold m-0 text-primary">Visitor Tracker</h3>
                    <p class="text-muted small m-0">Real-time traffic and location insights</p>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-md-end gap-3 mt-3 mt-md-0">
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" style="width: 250px;" placeholder="Search IP, City or ISP..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary px-3">Search</button>
                            @if(request('search'))
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                            @endif
                        </form>

                        <form action="{{ route('clicks.deleteall') }}" method="POST" onsubmit="return confirm('શું તમે બધો જ ડેટા ડિલીટ કરવા માંગો છો?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3">Clear Logs</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover m-0">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Network (ISP)</th> 
                        <th>Device & Browser</th>
                        <th>Time (IST)</th>
                        <th class="text-center">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark">{{ $row->ip }}</span>
                        </td>
                        <td>
                            <div class="location-wrapper">
                                <div class="city-text">
                                    <span class="me-1">📍</span>{{ $row->city }}, {{ $row->country }}
                                </div>
                                @if($row->latitude && $row->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                                       target="_blank" 
                                       class="btn-map">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-geo-alt-fill me-1" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                        View on Map
                                    </a>
                                @else
                                    <span class="no-gps">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            <path d="M8 13a5 5 0 0 1-5-5h1a4 4 0 1 0 8 0h1a5 5 0 0 1-5 5z"/>
                                        </svg>
                                        No GPS Data
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge-isp">{{ $row->isp ?? 'Generic ISP' }}</span>
                        </td> 
                        <td class="device-col">
                            {{ $row->device }}
                        </td>
                        <td class="timestamp">
                            {{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('d M, Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('h:i A') }}</small>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('click.delete', $row->id) }}" method="POST" onsubmit="return confirm('આ રેકોર્ડ ડિલીટ કરવો છે?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Delete Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                    </svg>
                                </button>
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
