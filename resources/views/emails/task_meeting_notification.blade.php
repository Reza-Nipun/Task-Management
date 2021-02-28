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
    </style>
</head>
<body>
<p>Dear Concern ,</p>
<p>You are invited to the following meeting.</p>
<table>
    <thead>
        <tr>
            <th>Meeting Date</th>
            <td>{{ $meeting_date }}</td>
        </tr>
        <tr>
            <th>Meeting Time</th>
            <td>{{ $meeting_time }}</td>
        </tr>
        <tr>
            <th>Meeting Link</th>
            <td>{{ $meeting_link }}</td>
        </tr>
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
            <td><a href="{{ url('/rescheduling_meeting_from_email/'.$meeting_id) }}" class="button">Meeting Reschedule Here</a></td>
            <td></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>