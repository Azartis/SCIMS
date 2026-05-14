<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.meta { font-size: 10px; color: #6b7280; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; font-size: 11px; }
        th { background-color: #f3f4f6; text-align: left; }
        tr:nth-child(even) td { background-color: #f9fafb; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p class="meta">
        Generated at: {{ $generatedAt->format('Y-m-d H:i:s') }}
    </p>

    <table>
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($headings as $heading)
                        <td>{{ $row[$heading] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

