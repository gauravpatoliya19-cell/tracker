<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Component - UI/UX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            padding: 50px; 
            font-family: 'Inter', sans-serif;
        }

        /* Container Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px;
        }

        /* Location Styling */
        .location-header {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            padding-bottom: 12px;
        }

        .city-name {
            display: block;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 6px;
        }

        /* The Map Smart Chip */
        .btn-map-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4; /* Soft Emerald */
            color: #16a34a;      /* Deep Emerald */
            border: 1px solid #bbf7d0;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-map-chip:hover {
            background: #16a34a;
            color: #ffffff;
            border-color: #16a34a;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
            transform: translateY(-1.5px);
        }

        .no-gps {
            font-size: 0.75rem;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="table-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th class="location-header border-0">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td class="border-0">
                        <span class="city-name">
                            <span style="color: #4361ee;">📍</span> {{ $row->city }}, {{ $row->country }}
                        </span>

                        @if($row->latitude && $row->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $row->latitude }},{{ $row->longitude }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn-map-chip">
                                
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                
                                View on Map
                            </a>
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
