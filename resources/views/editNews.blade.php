<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
        }
        .edit-form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .form-header h1 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-header p {
            color: #7f8c8d;
            margin-bottom: 0;
        }
        .form-label {
            font-weight: 500;
            color: #34495e;
            margin-bottom: 0.75rem;
        }
        .form-control {
            padding: 0.875rem 1rem;
            margin-bottom: 1.75rem;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #9c8c8c;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        textarea.form-control {
            min-height: 250px;
            resize: vertical;
        }
        .btn-update {
            padding: 0.875rem;
            font-weight: 600;
            background-color: #9c8c8c;
            border: none;
            letter-spacing: 0.5px;
            color: white;
        }

        .hidden-inputs {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-form-container">
            <div class="form-header">
                <h1>Edit News Article</h1>
                <p>Update the details of your news story</p>
            </div>
            
            <form action="{{ url('/dashboard/manage_news/edit_news/'.$news_id) }}" method="POST">
                @csrf
                <div class="hidden-inputs">
                    <input type="hidden" id="news_id" name="news_id" value="{{ $news_id }}">
                </div>
                
                <div class="mb-4">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="{{ $news->title }}" required placeholder="Enter your news title">
                </div>
                
                <div class="mb-4">
                    <label for="content" class="form-label">Article Content</label>
                    <textarea class="form-control" id="content" name="content" required
                              placeholder="Write your news content here">{{ $news->content }}</textarea>
                </div>
                
                <button type="submit" class=" btn-update w-100">
                    Update News Article
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>