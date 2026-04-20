<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f4f7f6; 
            padding: 30px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 25px;
            max-width: 900px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 15px;
        }

        .table thead {
            background-color: #f8fafc;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        .city-name {
            font-weight: 600;
            color: #1e293b;
            display: block;
            margin-bottom: 4px;
        }

        .btn-map-link {
            color: #4361ee;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-map-link:hover {
            text-decoration: underline;
            color: #3046bc;
        }

        .time-text {
            color: #475569;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-delete {
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 5px 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-card">
        
        <div class="header-section">
            <h4 class="fw-bold text-dark m-0">Dashboard</h4>
            <form action="{{ route('clicks.deleteall') }}" method="POST" onsubmit="return confirm('શું તમે બધો જ ડેટા ડિલીટ કરવા માંગો છો?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete All</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="border-0">Location</th>
                        <th class="border-0">Time</th>
                        <th class="border-0 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td class="py-3">
                            <span class="city-name">📍 {{ $row->city }}, {{ $row->country }}</span>
                            @if($row->latitude && $row->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                                   target="_blank" class="btn-map-link">
                                   View on Map
                                </a>
                            @else
                                <small class="text-muted" style="font-size: 0.75rem;">GPS Not Available</small>
                            @endif
                        </td>
                        
                        <td class="time-text">
                            {{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('d-m-Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('h:i A') }}</small>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('click.delete', $row->id) }}" method="POST" onsubmit="return confirm('આ રેકોર્ડ ડિલીટ કરવો છે?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($data->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">કોઈ ડેટા ઉપલબ્ધ નથી.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
                        <th class="border-0 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td class="py-3">
                            <span class="city-name">📍 {{ $row->city }}, {{ $row->country }}</span>
                            @if($row->latitude && $row->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                                   target="_blank" class="btn-map-link">
                                   View on Map
                                </a>
                            @else
                                <small class="text-muted" style="font-size: 0.75rem;">GPS Not Available</small>
                            @endif
                        </td>
                        
                        <td class="time-text">
                            {{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('d-m-Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('h:i A') }}</small>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('click.delete', $row->id) }}" method="POST" onsubmit="return confirm('આ રેકોર્ડ ડિલીટ કરવો છે?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($data->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">કોઈ ડેટા ઉપલબ્ધ નથી.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
                        <th class="border-0 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td class="py-3">
                            <span class="city-name">📍 {{ $row->city }}, {{ $row->country }}</span>
                            @if($row->latitude && $row->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                                   target="_blank" class="btn-map-link">
                                   View on Map
                                </a>
                            @else
                                <small class="text-muted" style="font-size: 0.75rem;">GPS Not Available</small>
                            @endif
                        </td>
                        
                        <td class="time-text">
                            {{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('d-m-Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->clicked_at)->timezone('Asia/Kolkata')->format('h:i A') }}</small>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('click.delete', $row->id) }}" method="POST" onsubmit="return confirm('આ રેકોર્ડ ડિલીટ કરવો છે?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($data->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">કોઈ ડેટા ઉપલબ્ધ નથી.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
                        @else
                            <span class="no-gps">GPS Not Available</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
