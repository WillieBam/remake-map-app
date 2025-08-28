<html>
<head>
    <title>{{ $news->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .content {
            margin-bottom: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #444;
        }
        p {
            font-size: 1.2em;
            line-height: 1.6;
            color: #555;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .view-count {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #5b5d5fff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="view-count">Views: {{ $news->views }}</div>
        <div class="content">
            <h1>{{ $news->title }}</h1>
            <p style="white-space: normal;">{{ $news->content }}</p>
        </div>
        <a href="{{ url('/dashboard') }}" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>