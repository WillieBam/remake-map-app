<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
        }
        .news-form {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            color: #343a40;
        }
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
        textarea.form-control {
            min-height: 200px;
            resize: vertical;
        }
        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            background-color: #9c8c8c;
            border: none;
            color: white;
        }
        
        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="news-form">
            <div class="form-header">
                <h1>Create News Article</h1>
                <p class="text-muted">Fill out the form below to publish a new news story</p>
            </div>
            
            <form action="{{ url('/dashboard/'.$user_id.'/manage_news/create_news') }}" method="POST">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">
                
                <div class="mb-3">
                    <label for="title" class="form-label">News Title</label>
                    <input type="text" class="form-control" id="title" name="title" required 
                           placeholder="Enter a compelling headline">
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">News Content</label>
                    <textarea class="form-control" id="content" name="content" required
                              placeholder="Write your news story here..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" id="country" name="country_id" required>
                        <option value="" selected disabled>Select a country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->country_id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class=" btn-submit">Publish News</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>