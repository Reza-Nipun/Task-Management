<html lang="en-US">
<head>
    <meta charset="text/html">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .button {
            background-color: yellow; /* Green */
            color: #000000;
            padding: 5px;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 20px;
            font-weight: 700;
        }

        .button_2 {
            background-color: green; /* Green */
            color: #ffffff;
            padding: 5px;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>
<p>Dear Concern ,</p>
<p>You have received new recurring task, which is mentioned below.</p>
<p style="background-color: orangered; color: white;">N.B.-This task will be generated tonight and will be enlisted to your MY TASK list.</p>
<table>
    <thead>
        <tr>
            <th>Task Name</th>
            <td>{{ $task_name }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $task_description }}</td>
        </tr>
        <tr>
            <th>Assigned By</th>
            <td>{{ $assigned_by }}</td>
        </tr>
        <tr>
            <th>Recurring Type</th>
            <td>{{ ($recurring_type == 0 ? 'Monthly' : ($recurring_type == 1 ? 'Weekly' : '')) }}</td>
        </tr>
        @if($recurring_type == 0)
            @if($last_date_of_month == 1)
                <tr>
                    <th>Last Date of Month?</th>
                    <td>{{ ($last_date_of_month == 0 ? 'NO' : ($last_date_of_month == 1 ? 'YES' : '')) }}</td>
                </tr>
            @elseif($last_date_of_month == 0)
                <tr>
                    <th>Month-Date</th>
                    <td>{{ $month_date }}</td>
                </tr>
            @endif
        @elseif($recurring_type == 1)
            <tr>
                <th>Week-Day</th>
                <td>{{ strtoupper($weekly_recurring_day) }}</td>
            </tr>
        @endif

    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>