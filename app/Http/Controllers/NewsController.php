<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Continent; 
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class newsController extends Controller
{
    // addNews
    function createNews(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $user = Auth::user();
        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->user_id = $user->user_id;
        $news->country_id = $request->input('country_id');
        $news->save();

        return redirect('/dashboard/manage_news');
    }

    //get create news form
    function viewCreateNews(){
        $user = Auth::user();
        $country = $user->country;
        $continent_id = $country->continent_id;
        $countries = Country::where('continent_id', $continent_id)->get();
        return view('createNews', ['countries' => $countries]);
    }

    //delete news
    function deleteNews(Request $request){
        $news = News::find($request->news_id);
        if ($news) {
            $this->authorize('delete', $news);
            $news->delete();
        }
        return redirect('/dashboard/manage_news');
    }

    //edit news
    function editNews(Request $request, $news_id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $news = News::find($request->news_id);
        $this->authorize('update', $news);
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->save();
        return redirect('/dashboard/manage_news');
    }

    //get edit news form
    function viewEditNews($news_id){
        $user = Auth::user();
        $news = News::find($news_id);
        return view('editNews', ['news_id' => $news_id, 'news' => $news]);
    }

    //view specific news
    function viewNews($news_id){
        $news = News::find($news_id);
        if (!$news) {
            return back()->with('error', 'News not found.');
        }
        return view('viewNews', ['news' => $news]);
    }


    //view all news list, with optional selected news
    function viewAllNews(Request $request, $news_id = null){
        $this->authorize('viewAny', News::class);
        $user = Auth::user();
        $allNews = News::all();
        $news = [];
        foreach ($allNews as $newsItem) {
            if ($user->can('view', $newsItem)) {
                $news[] = $newsItem;
            }
        }
        $selectedNews = null;
        if ($news_id) {
            $selectedNews = News::find($news_id);
        }
        return view('viewAllNews', [
            'news' => $news,
            'selectedNews' => $selectedNews
        ]);
    }

    //search for title
    //search for title or content (POST)
    public function searchNews(Request $request) {
        $search = $request->input('search');
        $order = $request->input('order', 'desc');
        $user = Auth::user();
        $allNews = News::query();
        if ($search) {
            $allNews = $allNews->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%");
            });
        }
        // Order logic
        if ($order === 'asc') {
            $allNews = $allNews->orderBy('created_at', 'asc');
        } elseif ($order === 'desc') {
            $allNews = $allNews->orderBy('created_at', 'desc');
        } elseif ($order === 'title_asc') {
            $allNews = $allNews->orderBy('title', 'asc');
        } elseif ($order === 'title_desc') {
            $allNews = $allNews->orderBy('title', 'desc');
        } elseif ($order === 'views_desc') {
            $allNews = $allNews->orderBy('views', 'desc');
        } elseif ($order === 'views_asc') {
            $allNews = $allNews->orderBy('views', 'asc');
        }
        $allNews = $allNews->get();
        $news = [];
        foreach ($allNews as $newsItem) {
            if ($user->can('view', $newsItem)) {
                $news[] = $newsItem;
            }
        }
        return view('viewAllNews', [
            'news' => $news,
            'selectedNews' => null
        ]);

    }
    

}
