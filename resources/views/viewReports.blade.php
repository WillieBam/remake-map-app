<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Reports - Dashboard</title>
        <style>
            .container {
                display: flex;
                width: 100%;
                height: 100vh;
            }

            .panel {
                width: 50%;
                padding: 20px;
                box-sizing: border-box;
            }

            .report {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }

            .report ul {
                padding: 0;
                display: flex;
            }

            .report ul li {
                margin: 5px 12px;
            }


        </style>
    </head>
    <body>
        <div class="container">
            <div class="panel">
                <h3>Reports</h3>
                <div>
                    <input id="search_report" value="{{ $search }}" style=" width: 500px; height: 30px; border: none; border-bottom: 2px solid gray;" type="text" name="search_report" placeholder="Search reports...">
                    <button style=" width: 70px; height: 30px; background: lightgreen;" onclick="query()">Search</button>
                
                    <select id="country-filter"">
                        <option value="0">All</option>
                        @php
                            $countries = array_unique(array_map(function($report) { return $report['message']['country']; }, $reports));
                        @endphp

                        @foreach ($countries as $country)
                        <option {{ $country['country_id'] == $filter ? "selected" : "" }} value="{{ $country['country_id'] }}">{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <script>
                    function query() {
                        fetch("{{ route('reports.query') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                keyword: document.getElementById("search_report").value,
                                country: document.getElementById("country-filter").value,
                            })
                        })
                        .then((response) => response.json())
                        .then((results) => {
                            let divReportsList = document.getElementById("reports-list");
                            let divReports = Array.from(divReportsList.children);

                            for (let divReport of divReports) {
                                divReport.style.display = 'none';

                                for (let result of results) {
                                    if (result.message.message_id == divReport.getAttribute('name')) {
                                        divReport.style.display = 'block';
                                        break;
                                    } else {
                                        divReport.style.display = 'none'; 
                                    }
                                }
                            }

                        })
                        .catch((error) => console.log(error));
                    }

                    query();
                </script>

                <div id="reports-list" style="max-height: 80vh; overflow-y: auto;">
                @foreach ($reports as $report)
                    @if ($selectedReport && $report['message']['message_id'] == $selectedReport['message']['message_id'])
                    <a name="{{ $report['message']['message_id'] }}" href="{{ route('reports.view') }}">
                    @else
                    <a name="{{ $report['message']['message_id'] }}" href="{{ route('reports.show', ['message_id' => $report['message']['message_id']]) }}">
                    @endif
                        <div class="report">
                            <ul>
                                <li>{{ $report['message']['user']['name'] }}</li>
                                <li>{{ $report['message']['country']['name'] }}</li>
                                <li>{{ $report['message']['created_at'] ?? "no date" }}</li>
                            </ul>
                            <p style="overflow-wrap: break-word">{{ $report['message']['content'] }}</p>
                            <p style="opacity: 0.5;">Report count(s): {{ $report['count'] }}</p>
                            <form method="POST" action="{{ route('message.delete', [$report['message']['country_id'], $report['message']['message_id']]) }}">
                                @csrf
                                <button type="submit"  style="background: red; padding: 5px 10px; color: white;">Delete</button>
                            </form>
                        </div>
                    </a>
                @endforeach
                </div>
            </div>
            <div class="panel">
                <h3>Report</h3>
                @if (empty($selectedReport))
                    <h2>No report selected</h2>
                @else
                    <div class="report">
                        <ul>
                            <li>{{ $selectedReport['message']['user']['name'] }}</li>
                            <li>{{ $selectedReport['message']['country']['name'] }}</li>
                            <li>{{ $selectedReport['message']['created_at'] ?? "no date" }}</li>
                        </ul>
                        <p style="overflow-wrap: break-word">{{ $selectedReport['message']['content'] }}</p>
                        <p style="opacity: 0.5;">Report count(s): {{ $selectedReport['count'] }}</p>
                        <form method="POST" action="{{ route('message.delete', [$selectedReport['message']['country_id'], $selectedReport['message']['message_id']]) }}">
                            @csrf
                            <button type="submit" style="background: red; padding: 5px 10px; color: white;">Delete</button>
                        </form>

                        <h4>Reported by:</h4>
                        <ul>
                        @foreach ($selectedReport['reports'] as $report)
                            <li>
                                <p>{{ $report['user']['name'] }}</p>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
</x-app-layout>