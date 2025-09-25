<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('News') }}
        </h2>
    </x-slot>

<html>
    <head>
        <title>All News</title>
        <style>
            .container {
                display: flex;
                gap: 32px;
                padding: 20px;
                background: #f4f4f9;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .news-list {
                flex: 1;
                width: 50%;
                list-style: none;
                padding: 0;
                max-height: 400px;
                overflow-y: auto;
                border: 1px solid #ccc;
                border-radius: 8px;
            }
            .news-item-container {
                display: block;
                padding: 16px;
                margin-bottom: 16px;
                background: #fff;
                border: 1px solid #ccc;
                border-radius: 8px;
                text-decoration: none;
                color: #333;
                font-weight: bold;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: background 0.3s, box-shadow 0.3s;
            }
            .news-item-container:hover {
                background: #e6e6e6;
                box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
            }
            .news-details {
                flex: 1;
                width: 50%;
                background: #fff;
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 0; /* Changed from 16px to 0 */
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                overflow: hidden; /* Added to contain children */
            }
            .selected-news {
                flex: 1; /* Changed from fixed height to flexible */
                padding: 20px;
                background: #f9f9f9;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .selected-title {
                font-size: 1.5em;
                font-weight: bold;
                text-align: center;
                word-wrap: break-word;
                overflow-wrap: break-word;
                padding-bottom: 12px;
                border-bottom: 1px solid #e0e0e0;
                margin: 0;
            }
            .selected-content {
                font-size: 1em;
                text-align: justify;
                overflow-wrap: break-word;
                line-height: 1.6;
                padding: 0 8px;
                margin: 0;
                flex: 1; /* Allow content to expand */
                overflow-y: auto; /* Make content scrollable if needed */
            }
            .action-buttons {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 12px;
                padding: 16px;
                background: #f5f5f5;
                border-top: 1px solid #e0e0e0;
            }
            .action-buttons button,
            .action-buttons a {
                background: #b8a6a6;
                color: #fff;
                border-radius: 6px;
                padding: 10px 20px;
                text-decoration: none;
                font-weight: bold;
                border: none;
                cursor: pointer;
                transition: background 0.3s;
                font-size: 0.9em;
            }
            .action-buttons button:hover,
            .action-buttons a:hover {
                background: #9c8c8c;
            }
            .search-bar {
                margin-bottom: 20px;
                display: flex;
                justify-content: flex-start;
                gap: 8px;
            }
            .search-bar input[type="text"] {
                padding: 8px;
                border-radius: 8px;
                border: 1px solid #ccc;
                width: 250px;
            }
            .search-bar button {
                background: #b8a6a6;
                color: #fff;
                border-radius: 8px;
                padding: 8px 16px;
                font-weight: bold;
                border: none;
                cursor: pointer;
            }
            .search-bar button:hover {
                background: #9c8c8c;
            }
            .create-button {
                display: inline-block;
                background: #9c8c8c;
                color: #fff;
                border-radius: 8px;
                padding: 10px 24px;
                text-decoration: none;
                font-weight: bold;
                border: none;
                cursor: pointer;
                font-size: 1em;
                transition: background 0.3s;
                margin-top: 16px;
            }
            /* Added for the empty state */
            .empty-state {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                padding: 20px;
            }
            
            /* NEW STYLES FOR NEWS ITEM METADATA */
            .news-item-meta {
                display: flex;
                justify-content: space-between;
                margin-top: 8px;
                font-size: 0.8em;
                color: #666;
                font-weight: normal;
            }
            .news-views {
                display: flex;
                align-items: center;
            }
            .news-date {
                display: flex;
                align-items: center;
            }
            .news-title {
                font-size: 1.1em;
                margin-bottom: 8px;
                line-height: 1.4;
            }
        </style>
    </head>
    <body>
        <h1>All News</h1>
    <form class="search-bar" method="POST" action="{{ url('/dashboard/manage_news/search') }}">
            @csrf
            <input type="text" name="search" placeholder="Search news..." value="{{ old('search', request('search')) }}">
            <select name="order">
                <option value="desc" {{ (old('order', request('order')) == 'desc') ? 'selected' : '' }}>Newest First</option>
                <option value="asc" {{ (old('order', request('order')) == 'asc') ? 'selected' : '' }}>Oldest First</option>
                <option value="title_asc" {{ (old('order', request('order')) == 'title_asc') ? 'selected' : '' }}>Title A-Z</option>
                <option value="title_desc" {{ (old('order', request('order')) == 'title_desc') ? 'selected' : '' }}>Title Z-A</option>
                <option value="views_desc" {{ (old('order', request('order')) == 'views_desc') ? 'selected' : '' }}>Most Viewed</option>
                <option value="views_asc" {{ (old('order', request('order')) == 'views_asc') ? 'selected' : '' }}>Least Viewed</option>
            </select>
            <button type="submit">Search</button>
        </form>
        <div class="container">
            <div class="news-list">
                @foreach($news as $newsItem)
                    <a href="{{ url('/dashboard/manage_news/'.$newsItem['news_id']) }}" class="news-item-container">
                        <div class="news-title">{{ $newsItem['title'] }}</div>
                        <div class="news-item-meta">
                            <div class="news-views"> {{ $newsItem['views'] }} views</div>
                            <div class="news-date"> {{ \Carbon\Carbon::parse($newsItem['created_at'])->format('M j, Y') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="news-details">
                @if(isset($selectedNews) && $selectedNews)
                    <div class="selected-news">
                        <h2 class="selected-title">{{ $selectedNews->title }}</h2>
                        <div class="selected-content">
                            <p>{{ $selectedNews->content }}</p>
                        </div>
                    </div>
                    <div class="action-buttons">
                        @can('delete', $selectedNews)
                            <form action="{{ url('/dashboard/manage_news/delete_news/'.$selectedNews->news_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="news_id" value="{{ $selectedNews->news_id }}">
                                <button type="submit">Delete</button>
                            </form>
                        @endcan
                        @can('update', $selectedNews)
                            <form action="{{ url('/dashboard/manage_news/edit_news/'.$selectedNews->news_id ) }}" method="GET" class="action-form" style="display:inline;">
                                <button type="submit">Edit</button>
                            </form>
                        @endcan
                        <form action="{{ url('/dashboard/manage_news') }}" method="GET" class="action-form" style="display:inline;">
                            <button type="submit">Back</button>
                        </form>
                    </div>
                @else
                    <div class="empty-state">
                        <a href="{{ url('/dashboard/manage_news/create_news') }}" class="create-button">Create News</a>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
</x-app-layout>
