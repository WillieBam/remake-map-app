<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('World Map & Community') }}
        </h2>
    </x-slot>

    <!-- Main Container: Map + Overlay UI -->
    <div class="relative w-full h-[calc(100vh-65px)] overflow-hidden bg-slate-900">
        
        <!-- 1. MapTiler Canvas Container -->
        <div id="map" class="w-full h-full"></div>

        <!-- 2. Floating Search & Continent Filter Bar -->
        <div class="absolute top-4 left-1/2 -translate-x-1/2 z-20 w-11/12 max-w-xl">
            <div class="bg-white/90 backdrop-blur-md shadow-lg rounded-xl p-2 flex items-center gap-2 border border-gray-200">
                <input 
                    type="text" 
                    id="country-search" 
                    placeholder="Search countries (e.g. Japan, France)..." 
                    class="w-full px-3 py-1.5 text-sm bg-transparent border-0 focus:ring-0 text-gray-800 placeholder-gray-400"
                    autocomplete="off"
                >
                <select id="continent-select" class="text-xs bg-gray-100 border-0 rounded-lg px-2 py-1.5 text-gray-700 focus:ring-0">
                    <option value="0">All Continents</option>
                </select>
            </div>
            <!-- Auto-suggestions dropdown -->
            <ul id="search-suggestions" class="hidden mt-1 max-h-60 overflow-y-auto bg-white rounded-xl shadow-xl border border-gray-100 divide-y divide-gray-100"></ul>
        </div>

        <!-- 3. Country Details Slide-Out Drawer -->
        <div id="country-drawer" class="absolute top-4 right-4 bottom-4 w-96 max-w-[90vw] bg-white/95 backdrop-blur-lg shadow-2xl rounded-2xl z-30 flex flex-col transition-transform duration-300 transform translate-x-[110%] border border-gray-100">
            <!-- Drawer Header -->
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
               <div>
                  <h3 id="drawer-country-name" class="text-lg font-bold text-gray-900">Country</h3>
                  <div class="flex items-center gap-2 mt-1">
                      <span id="drawer-continent-name" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Continent</span>
                      <span id="drawer-population" class="text-xs text-gray-500 hidden"></span>
                  </div>
              </div>
                <button onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-200/50">
                    ✕
                </button>
            </div>

            <!-- Tabs: Posts vs News -->
            <div class="flex border-b border-gray-100 text-sm font-medium">
                <button id="tab-posts-btn" onclick="switchTab('posts')" class="flex-1 py-2.5 text-center text-blue-600 border-b-2 border-blue-600">Messages</button>
                <button id="tab-news-btn" onclick="switchTab('news')" class="flex-1 py-2.5 text-center text-gray-500 hover:text-gray-700">News</button>
            </div>

            <!-- Drawer Body -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Messages Tab -->
                <div id="tab-posts">
                    <!-- Post Form (for authenticated users) -->
                    <div id="post-form-container" class="mb-4">
                        <textarea id="post-content" placeholder="Share your thoughts about this country..." rows="3" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                        <button onclick="submitMessage()" class="mt-2 w-full py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow-sm">Post Message</button>
                    </div>
                    <div id="posts-list" class="space-y-3"></div>
                </div>

                <!-- News Tab -->
                <div id="tab-news" class="hidden space-y-3">
                    <div id="news-list" class="space-y-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MapTiler Interaction Script -->
    <script>
       const MAPTILER_KEY = '{{ config('services.maptiler.key') }}';
        let map, countriesRegistry = [], activeCountryId = null;

        document.addEventListener('DOMContentLoaded', async () => {
            // fetch countries from backend for client-side matching
            const res = await fetch('/countries-list');
            countriesRegistry = await res.json();
            populateContinentFilter();

            // init MapTiler Map
            maptilersdk.config.apiKey = MAPTILER_KEY;
            map = new maptilersdk.Map({
                container: 'map',
                style: maptilersdk.MapStyle.DATAVIZ.LIGHT,
                center: [15, 25],
                zoom: 1.8,
            });

            // add Vector Administrative Country Layer
            map.on('load', () => {
                map.addSource('maptiler-countries', {
                    type: 'vector',
                    url: `https://api.maptiler.com/tiles/countries/tiles.json?key=${MAPTILER_KEY}`
                });

                // Level 0 represents country polygons
                map.addLayer({
                    id: 'country-polygons',
                    type: 'fill',
                    source: 'maptiler-countries',
                    'source-layer': 'administrative',
                    filter: ['==', 'level', 0],
                    paint: {
                        'fill-color': '#3b82f6',
                        'fill-opacity': 0.1,
                        'fill-outline-color': '#1d4ed8'
                    }
                });

                // Hover Highlight Effect
                map.on('mousemove', 'country-polygons', () => {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'country-polygons', () => {
                    map.getCanvas().style.cursor = '';
                });

                // 4. Click Country on Map
                map.on('click', 'country-polygons', async (e) => {
                    if (!e.features || e.features.length === 0) return;
                    const feature = e.features[0];
                    const countryName = feature.properties.name || feature.properties['name:en'];

                    // Match with database country
                    const matched = findCountry(countryName);
                    if (matched) {
                        selectCountry(matched.country_id, matched.name);
                    }
                });
            });

            setupSearchListeners();
        });

        // Match country by name (case-insensitive)
        function findCountry(name) {
            if (!name) return null;
            const clean = name.toLowerCase().trim();
            return countriesRegistry.find(c => c.name.toLowerCase() === clean || c.name.toLowerCase().includes(clean));
        }

        // Select Country: zooms map and loads drawer content
        async function selectCountry(countryId, countryName) {
            activeCountryId = countryId;
            openDrawer();

            // Fetch Country Data from Laravel API
            const response = await fetch(`/countries/${countryId}/data`);
            const data = await response.json();

            // Update Drawer
            document.getElementById('drawer-country-name').innerText = data.country.name;
            document.getElementById('drawer-continent-name').innerText = data.country.continent_name;
            
            // Update Population from vizData
            const population = getCountryPopulation(data.country.name);
            const popElement = document.getElementById('drawer-population');

            if (population) {
                popElement.innerText = `👥 Population: ${population}`;
                popElement.classList.remove('hidden');
            } else {
                popElement.classList.add('hidden');
            }

            // render messages
            renderMessages(data.messages);
            renderNews(data.news);

            // Geocode and animate map to country
            try {
                const geoRes = await maptilersdk.geocoding.forward(countryName, { types: ['country'] });
                if (geoRes.features && geoRes.features.length > 0) {
                    const feat = geoRes.features[0];
                    if (feat.bbox) {
                        map.fitBounds(feat.bbox, { padding: 60, duration: 1200 });
                    } else if (feat.center) {
                        map.flyTo({ center: feat.center, zoom: 4.5, duration: 1200 });
                    }
                }
            } catch (err) {
                console.log('Geocoding notice:', err);
            }
        }

        // Render Posts in Drawer
        function renderMessages(messages) {
            const list = document.getElementById('posts-list');
            if (messages.length === 0) {
                list.innerHTML = '<p class="text-xs text-gray-400 italic">No messages yet. Be the first to post!</p>';
                return;
            }
            list.innerHTML = messages.map(m => `
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                    <p class="text-gray-800 break-words mb-1">${m.content}</p>
                    <div class="flex justify-between items-center text-[10px] text-gray-400">
                        <span>${m.user_name} • ${m.created_at}</span>
                        <span>${m.views} views</span>
                    </div>
                </div>
            `).join('');
        }

        // Render News in Drawer
        function renderNews(news) {
            const list = document.getElementById('news-list');
            if (news.length === 0) {
                list.innerHTML = '<p class="text-xs text-gray-400 italic">No news published for this country.</p>';
                return;
            }
            list.innerHTML = news.map(n => `
                <a href="${n.url}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-100 transition">
                    <h4 class="font-semibold text-gray-900 text-xs">${n.title}</h4>
                    <p class="text-[11px] text-gray-500 line-clamp-2 mt-1">${n.content}</p>
                    <span class="text-[10px] text-gray-400 mt-2 block">${n.created_at} • ${n.views} views</span>
                </a>
            `).join('');
        }

        // Drawer Controls
        function openDrawer() {
            document.getElementById('country-drawer').classList.remove('translate-x-[110%]');
        }
        function closeDrawer() {
            document.getElementById('country-drawer').classList.add('translate-x-[110%]');
        }
        function switchTab(tab) {
            document.getElementById('tab-posts').classList.toggle('hidden', tab !== 'posts');
            document.getElementById('tab-news').classList.toggle('hidden', tab !== 'news');
            document.getElementById('tab-posts-btn').className = tab === 'posts' ? 'flex-1 py-2.5 text-center text-blue-600 border-b-2 border-blue-600' : 'flex-1 py-2.5 text-center text-gray-500 hover:text-gray-700';
            document.getElementById('tab-news-btn').className = tab === 'news' ? 'flex-1 py-2.5 text-center text-blue-600 border-b-2 border-blue-600' : 'flex-1 py-2.5 text-center text-gray-500 hover:text-gray-700';
        }

        // Search Input & Suggestions
        function setupSearchListeners() {
            const searchInput = document.getElementById('country-search');
            const suggestions = document.getElementById('search-suggestions');
            const continentSelect = document.getElementById('continent-select');

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                const selectedContinent = continentSelect ? continentSelect.value : '0';

                if (!query) {
                    suggestions.classList.add('hidden');
                    return;
                }

            const filtered = countriesRegistry
                .filter(c => {
                    const matchesName = c.name.toLowerCase().includes(query);
                    const matchesContinent = selectedContinent === '0' || String(c.continent_id) === String(selectedContinent);
                    return matchesName && matchesContinent;
                })
                .slice(0, 8);

                suggestions.innerHTML = filtered.map(c => `
                    <li onclick="selectCountry(${c.country_id}, '${c.name.replace(/'/g, "\\'")}'); document.getElementById('search-suggestions').classList.add('hidden');" 
                        class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-xs flex justify-between">
                        <span class="font-medium text-gray-800">${c.name}</span>
                        <span class="text-gray-400">${c.continent_name}</span>
                    </li>
                `).join('');
                suggestions.classList.remove('hidden');
            });
        }

        // Populate continent dropdown from the countries
        function populateContinentFilter() {
          const select = document.getElementById('continent-select');
          if(!select) return ;

          const continentMap = new Map();
          countriesRegistry.forEach(c => {
            if (c.continent_id && c.continent_name) {
              continentMap.set(c.continent_id, c.continent_name)
            }
          });

          Array.from(continentMap.entries()).sort((a,b)=>a[1].localeCompare(b[1]))
          .forEach(([id,name])=> {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            select.appendChild(option);
          });

          select.addEventListener('change', ()=> {
            const searchInput = document.getElementById('country-search');
            if(searchInput && searchInput.value) {
              searchInput.dispatchEvent(new Event('input'));
            }

          });

        }

        // Post a message via AJAX
        async function submitMessage() {
            const content = document.getElementById('post-content').value;
            if (!content || !activeCountryId) return;

            const res = await fetch(`/countries/${activeCountryId}/create-message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ content })
            });

            if (res.ok) {
                document.getElementById('post-content').value = '';
                selectCountry(activeCountryId, document.getElementById('drawer-country-name').innerText);
            }else if (res.status == 422) {
              const data = await res.json();
              alert(data.errors?.content?.[0] || 'Validation error: message must be at least  10 characters.');
            }
        }

        const vizData = {
          "8":   { "name": "Albania", "population": 2829741 },
          "40":  { "name": "Austria", "population": 8932664 },
          "56":  { "name": "Belgium", "population": 11566041 },
          "100": { "name": "Bulgaria", "population": 6916548 },
          "191": { "name": "Croatia", "population": 4036355 },
          "196": { "name": "Cyprus", "population": 896005 },
          "203": { "name": "Czechia", "population": 10701777 },
          "208": { "name": "Denmark", "population": 5840045 },
          "233": { "name": "Estonia", "population": 1330068 },
          "246": { "name": "Finland", "population": 5533793 },
          "250": { "name": "France", "population": 67439599 },
          "276": { "name": "Germany", "population": 83155031 },
          "300": { "name": "Greece", "population": 10682547 },
          "348": { "name": "Hungary", "population": 9730772 },
          "352": { "name": "Iceland", "population": 368792 },
          "372": { "name": "Ireland", "population": 5006907 },
          "380": { "name": "Italy", "population": 59257566 },
          "428": { "name": "Latvia", "population": 1893223 },
          "438": { "name": "Liechtenstein", "population": 39055 },
          "440": { "name": "Lithuania", "population": 2795680 },
          "442": { "name": "Luxembourg", "population": 634730 },
          "470": { "name": "Malta", "population": 516100 },
          "499": { "name": "Montenegro", "population": 620739 },
          "528": { "name": "Netherlands", "population": 17475415 },
          "578": { "name": "Norway", "population": 5391369 },
          "616": { "name": "Poland", "population": 37840001 },
          "620": { "name": "Portugal", "population": 10298252 },
          "642": { "name": "Romania", "population": 19186201 },
          "688": { "name": "Serbia", "population": 6871547 },
          "703": { "name": "Slovakia", "population": 5459781 },
          "705": { "name": "Slovenia", "population": 2108977 },
          "724": { "name": "Spain", "population": 47394223 },
          "752": { "name": "Sweden", "population": 10379295 },
          "756": { "name": "Switzerland", "population": 8667088 },
          "792": { "name": "Turkey", "population": 83614362 },
          "807": { "name": "North Macedonia", "population": 2068808 }
      };

        function getCountryPopulation(countryName){
          if (!countryName) return null;
          const entry = Object.values(vizData).find(item=> item.name.toLowerCase() === countryName.toLowerCase());
          
          return entry ? entry.population.toLocaleString() : null;
        }
    </script>
</x-app-layout>