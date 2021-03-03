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
<p>Following task has been rescheduled.</p>

{{--@if(isset($system_message))--}}
    {{--<p style="background-color: yellow;">{{ $system_message }}</p>--}}
{{--@endif--}}

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
            <th>Delivery Date</th>
            <td>{{ $delivery_date }}</td>
        </tr>
        <tr>
            <th>Reschedule Date</th>
            <td>{{ $reschedule_delivery_date }}</td>
        </tr>
        <tr>
            <th>Change Count</th>
            <td>{{ $change_count }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>{{ $remarks }}</td>
        </tr>
        <tr>
            <th><a href="{{ url('/schedule_task_completion_meeting/'.$task_id) }}" class="button">Schedule Task Completion Meeting</a></th>
            <td><a href="{{ url('/task_confirmation/'.$task_id) }}" class="button_2">Task Confirmation</a></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>