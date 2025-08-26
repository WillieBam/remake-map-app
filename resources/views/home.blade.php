<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Countries List</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .page-header {
      background-color: #4CAF50;
      color: white;
      padding: 30px 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .country-card {
      background: white;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s, box-shadow 0.2s;
      border-left: 4px solid #4CAF50;
    }

    .country-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .country-link {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      font-size: 18px;
      display: block;
      padding: 10px;
    }

    .country-link:hover {
      color: #4CAF50;
    }

    .country-region {
      color: #6c757d;
      font-size: 0.9em;
      margin-left: 10px;
    }

    .search-box {
      margin-bottom: 30px;
    }

    .message-card {
      background: #f9f9f9;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .message-content {
      margin-bottom: 10px;
    }

    .success-notification {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #4CAF50;
      color: white;
      padding: 15px 20px;
      border-radius: 4px;
      z-index: 2000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .add-message-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
    }

    .add-message-btn:hover {
      background-color: #45a049;
      color: white;
    }

    .country-counter {
      background-color: #e9ecef;
      color: #495057;
      padding: 3px 8px;
      border-radius: 10px;
      font-size: 0.8em;
      margin-left: 10px;
    }
  </style>
</head>

<body>



  <div class="container">
    <div class="page-header">
      <h1>Countries</h1>
    </div>

    <!--search bar-->
    <div>
      <div>
        <form action="{{route('countries.search')  }}" method="GET">
          <span class="input-group-text"></span>
          <input type="text" name="search_country" placeholder="Search countries...">
          <button type="submit">Search</button>
        </form>
      </div>
    </div>



    <!--message board-->
    @if(isset($country_data))
    <button type="button" class="btn add-message-btn" id="post-message" onclick="toggleForm()" > Post Message </button>
  
    <div id="post-message-form" style="display:none; margin-top:10px;" >
      <h3>Message for {{$country_data->name}} </h3>
     
      <form  
        action="{{ route('message.add', ['id' => $country_data->country_id]) }}"
        method="POST">
         @csrf  
            
            <textarea name="content" rows="5" placeholder="Write your message..."></textarea>
            @error('content')
            <div style="color:red">{{ $message }}</div>
            @enderror
            <input type="hidden" name="user_id" value={{ auth()->id() }}>  
            <button type="submit">Submit</button>
        </form>
    </div>
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif
    
    <div id="messages-list">
      <h2>Messages</h2>
      @forelse($messages as $message)
      <p>{{ $message->content}}<i id="report-flag" class="bi bi-flag" style="text-align:right; margin-left:30px;"></i></p>      
      <div>Posted on {{ date('M d, Y', strtotime($message->created_at)) }} • {{ $message->views }} views </div>
      @empty
      <p>No messages</p>
      @endforelse
    </div>  

    <script>
              
      const form = document.getElementById('post-message-form');
      const list = document.getElementById('messages-list');
      const postButton = document.getElementById('post-message');

      function showList(){
          form.style.display = "none";
          list.style.display = "block";
          postButton.style.display = "block";
      }
      function showForm(){
        form.style.display = "block";
        list.style.display = "none";
        postButton.style.display="none";

      }

      function toggleForm(){
          if (form.style.display === "none"){
            showForm();
           
          }else{
            showList();
            window.history.pushState({view:'list'}, "", window.location.pathname);
        }
      }
        
    window.addEventListener("popstate",function(event){
      if(event.state && event.state.view === "form"){
        showForm();
      }else{
        showList();
      }
    });

    window.addEventListener("DOMContentLoaded", function(){
      if(window.location.pathname.endsWith('/create-message')){
        showForm();
        window.history.replaceState({view:"form"},"");
      }else{
        showList();
        window.history.replaceState({view:"list"},"");
      }
    });
    </script>


    <!--news board-->
      <h2>News</h2>
      @forelse($news as $n)
      <p>{{ $n->title }}</p>
      <div>{{ Str::limit($n->content, 100)}}</div>
      <div>Posted on {{ date('M d, Y', strtotime($n->created_at)) }} • {{ $n->views }} views </div>
      @empty
      <p>No news</p>
      @endforelse
  @endif

    <!-- country list -->
    @if($countries->count() > 0)
    <div id="countries-list" class="row">
      @foreach($countries as $country)
      <div>
      <div>
      <a href="{{ route('countries.show', ['id' => $country->country_id]) }}">
      <span>{{$country->name}}</span>
      @if(isset($country->continent_id))
      <span>Continent #{{ $country->continent_id }}</span>
      @endif
      </a>
      </div>
      </div>
    @endforeach
    </div>

    <!--search result-->
  @elseif ($search_results->count() > 0)
    <div id="countries-list" class="row">
      @foreach($search_results as $search)
      <div>
      <div>
      <a href="{{ route('countries.show', ['id' => $search->country_id]) }}">
      <span>{{$search->name}}</span>
      @if(isset($search->continent_id))
      <span>Continent #{{ $search->continent_id }}</span>
      @endif
      </a>
      </div>
      </div>
    @endforeach
    </div>
  @else
    <div>
      No countries available.
    </div>
  @endif



</body>

</html>

<!--
<script>
      const vizData = {
        "8":{"name":"Albania","population":2829741},
        "40":{"name":"Austria","population":8932664},
        "56":{"name":"Belgium","population":11566041},
        "100":{"name":"Bulgaria","population":6916548},
        "191":{"name":"Croatia","population":4036355},
        "196":{"name":"Cyprus","population":896005},
        "203":{"name":"Czechia","population":10701777},
        "208":{"name":"Denmark","population":5840045},
        "233":{"name":"Estonia","population":1330068},
        "246":{"name":"Finland","population":5533793},
        "250":{"name":"France","population":67439599},
        "276":{"name":"Germany","population":83155031},
        "300":{"name":"Greece","population":10682547},
        "348":{"name":"Hungary","population":9730772},
        "352":{"name":"Iceland","population":368792},
        "372":{"name":"Ireland","population":5006907},
        "380":{"name":"Italy","population":59257566},
        "428":{"name":"Latvia","population":1893223},
        "438":{"name":"Liechtenstein","population":39055},
        "440":{"name":"Lithuania","population":2795680},
        "442":{"name":"Luxembourg","population":634730},
        "470":{"name":"Malta","population":516100},
        "499":{"name":"Montenegro","population":620739},
        "528":{"name":"Netherlands","population":17475415},
        "578":{"name":"Norway","population":5391369},
        "616":{"name":"Poland","population":37840001},
        "620":{"name":"Portugal","population":10298252},
        "642":{"name":"Romania","population":19186201},
        "688":{"name":"Serbia","population":6871547},
        "703":{"name":"Slovakia","population":5459781},
        "705":{"name":"Slovenia","population":2108977},
        "724":{"name":"Spain","population":47394223},
        "752":{"name":"Sweden","population":10379295},
        "756":{"name":"Switzerland","population":8667088},
        "792":{"name":"Turkey","population":83614362},
        "807":{"name":"North Macedonia","population":2068808}
      };
      function setStates() {
        const app = map.querySourceFeatures('')
        const countries = map.querySourceFeatures('statesData', {
          sourceLayer: 'administrative',
          filter: ['all', ['==', 'level', 0]],
        });
        countries.forEach(country => {
          if(country.id && vizData[country.id]) {
            map.setFeatureState({
              source: 'statesData',
              sourceLayer: 'administrative',
              id: country.id
            }, {
              population: vizData[country.id].population
            });
          }
        });
        if (countries.length !== 0) {
          map.off('data', afterLoad);
        }
      }
      function afterLoad() {
        if (map.getSource('statesData') && map.isSourceLoaded('statesData')) {
          setStates();
        }
      }
      maptilersdk.config.apiKey = 'IGTDvcygmswFVmuJFuiU';
      const map = new maptilersdk.Map({
        container: 'map', // container's id or the HTML element to render the map
        style: maptilersdk.MapStyle.DATAVIZ.LIGHT,
        center: [13.39, 52.51], // starting position [lng, lat]
        zoom: 2, // starting zoom
      });

      map.on('load', function() {
        map.addSource('statesData', {
          type: 'vector',
          url: `https://api.maptiler.com/data/0198a7a2-350d-79aa-9240-3c5ededf3064/features.json?key=IGTDvcygmswFVmuJFuiU`,
        });

        // Find the id of the first symbol layer in the map style
        const layers = map.getStyle().layers;
        const firstSymbolId = layers.find(layer => layer.type === 'symbol');

          map.addLayer(
              {
                  'id': 'countries',
                  'source': 'statesData',
                  'source-layer': 'administrative',
                  'type': 'fill',
                  'paint': {
                      'fill-color': '#6B7C93',
                      'fill-opacity': 1,
                      'fill-outline-color': '#000'
                  }
              },
              firstSymbolId
          );
      });
      map.on('data', afterLoad);
      map.on('click', 'countries', function (e) {
        console.log(e);
      new maptilersdk.Popup()
        .setLngLat(e.lngLat)
        .setHTML(`<h3>Population</h3><p>${e.features[0].state.population.toLocaleString()}</p>`)
        .addTo(map);
          
      });
      // Change the cursor to a pointer when the mouse is over the layer.
      map.on('mouseenter', 'countries', function () {
          map.getCanvas().style.cursor = 'pointer';
      });

      // Change it back to a pointer when it leaves.
      map.on('mouseleave', 'countries', function () {
          map.getCanvas().style.cursor = '';
      });
  </script>
-->