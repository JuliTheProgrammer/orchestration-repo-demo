<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Notes' }}</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; color: #1f2933; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .status { background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem; }
        .errors { background: #fee2e2; color: #991b1b; padding: 0.5rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem; }
        .note { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; }
        .note h2 { margin: 0 0 0.5rem; font-size: 1.1rem; }
        .actions { margin-top: 0.5rem; }
        .actions form { display: inline; }
        form.field { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; }
        input[type=text], textarea { padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; font: inherit; }
        button { cursor: pointer; padding: 0.5rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: #f8fafc; font: inherit; }
        button:hover { background: #e2e8f0; }
    </style>
</head>
<body>
    <header>
        <h1><a href="{{ route('notes.index') }}">Notes</a></h1>
    </header>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main>
        {{ $slot }}
    </main>
</body>
</html>
