<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? 'Custom Report' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #607D8B; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $template->name ?? 'Custom Report' }}</h1>
        <p>{{ now()->format('d M Y H:i') }}</p>
    </div>

    @if(isset($data) && is_array($data))
    <table>
        <thead>
            <tr>
                @foreach(array_keys($data[0] ?? []) as $column)
                <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                @foreach($row as $value)
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No data available</p>
    @endif
</body>
</html>
