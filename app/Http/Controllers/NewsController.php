<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Continent; 
use App\Models\News;
use App\Models\User;

class newsController extends Controller
{
    // addNews
    function createNews(Request $request){
        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->user_id = $request->input('user_id');
        $news->country_id = $request->input('country_id');
        $news->save();

        return redirect('/dashboard/' . $news->user_id . '/manage_news');
    }

    //get create news form
    function viewCreateNews($user_id){

        // Fetch the user and their country
        $user = User::find($user_id);
        $country = $user->country;
        $continent_id = $country->continent_id;
        $countries = Country::where('continent_id', $continent_id)->get();

        return view('createNews', ['user_id' => $user_id, 'countries' => $countries]);
    }

    function deleteNews(Request $request){
        $news = News::find($request->news_id);
        if ($news) {
            $news->delete();
            return redirect('/dashboard/' . $request->user_id . '/manage_news');
        }
        return redirect('/dashboard/' . $request->user_id . '/manage_news');
    }

    //edit news
    function editNews(Request $request, $news_id){

        $news = News::find($request->news_id);
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->save();

        return redirect('/dashboard/' . $request->user_id . '/manage_news');
    }

    //get edit news form
    function viewEditNews($user_id, $news_id){

        $news = News::find($news_id);
        return view('editNews', ['user_id' => $user_id, 'news_id' => $news_id, 'news' => $news]);
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
    function viewAllNews($user_id, $news_id = null){
        $user = User::find($user_id);
        $country = $user->country;
        $continent_id = $country->continent_id;

        $countries = Country::where('continent_id', $continent_id)->get();
        $news = [];

        // Gather all news from countries in the continent
        foreach ($countries as $country) {
            $news = array_merge($news, $country->News->toArray());
        }

        // Re-index array for Blade compatibility
        $news = array_values($news);

        $selectedNews = null;
        if ($news_id) {
            $selectedNews = News::find($news_id);
        }

        return view('viewAllNews', [
            'news' => $news,
            'user_id' => $user_id,
            'selectedNews' => $selectedNews
        ]);
    }

    //search for title
    //search for title or content (POST)
    public function searchNews(Request $request, $user_id) {
        $search = $request->input('search');
        $order = $request->input('order', 'desc');
        $user = User::find($user_id);
        $country = $user->country;
        $continent_id = $country->continent_id;
        $countries = Country::where('continent_id', $continent_id)->get();
        $news = [];
        foreach ($countries as $country) {
            $news = array_merge($news, $country->News->toArray());
        }
        if ($search) {
            $news = array_filter($news, function($item) use ($search) {
                return stripos($item['title'], $search) !== false || stripos($item['content'], $search) !== false;
            });
        }
        // Order logic
        if ($order === 'asc') {
            usort($news, function($a, $b) {
                return strtotime($a['created_at']) <=> strtotime($b['created_at']);
            });
        } elseif ($order === 'desc') {
            usort($news, function($a, $b) {
                return strtotime($b['created_at']) <=> strtotime($a['created_at']);
            });
        } elseif ($order === 'title_asc') {
            usort($news, function($a, $b) {
                return strcmp($a['title'], $b['title']);
            });
        } elseif ($order === 'title_desc') {
            usort($news, function($a, $b) {
                return strcmp($b['title'], $a['title']);
            });
        } elseif ($order === 'views_desc') {
            usort($news, function($a, $b) {
                return ($b['views'] ?? 0) <=> ($a['views'] ?? 0);
            });
        } elseif ($order === 'views_asc') {
            usort($news, function($a, $b) {
                return ($a['views'] ?? 0) <=> ($b['views'] ?? 0);
            });
        }
        $news = array_values($news);
        return view('viewAllNews', [
            'news' => $news,
            'user_id' => $user_id,
            'selectedNews' => null
        ]);
    }

    

}
