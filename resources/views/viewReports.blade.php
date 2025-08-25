<!DOCTYPE html>
<html>
    <head>
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
                <form method="POST" action="{{ route('searchReports') }}">
                    @csrf
                    <input type="text" name="search" value="{{ old('search', '') }}" placeholder="Search reports">
                </form>
                <div>
                @foreach ($reports as $report)
                    @if ($selectedReport && $report['message']['message_id'] == $selectedReport['message']['message_id'])
                    <a href="{{ route('viewReports') }}">
                    @else
                    <a href="{{ route('viewReportsWithId', ['message_id' => $report['message']['message_id']]) }}">
                    @endif
                        <div class="report">
                            <ul>
                                <li>{{ $report['message']['getUser']['name'] }}</li>
                                <li>{{ $report['message']['getUser']['country']['name'] }}</li>
                                <li>{{ $report['message']['created_at'] ?? "no date" }}</li>
                            </ul>
                            <p>{{ $report['message']['content'] }}</p>
                            <p>{{ $report['count'] }}</p>
                            <form method="POST">
                                @csrf
                                <input type="hidden" name="messsage_id" value="{{ $report['message']['message_id'] }}">
                                <button type="submit">Delete</button>
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
                            <li>{{ $selectedReport['message']['getUser']['name'] }}</li>
                            <li>{{ $selectedReport['message']['getUser']['country']['name'] }}</li>
                            <li>{{ $selectedReport['message']['created_at'] ?? "no date" }}</li>
                        </ul>
                        <p>{{ $selectedReport['message']['content'] }}</p>
                        <p>{{ $selectedReport['count'] }}</p>
                        <form method="POST">
                            @csrf
                            <input type="hidden" name="messsage_id" value="{{ $selectedReport['message']['message_id'] }}">
                            <button type="submit">Delete</button>
                        </form>

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