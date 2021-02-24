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
    </style>
</head>
<body>
<p>Dear Concern ,</p>
<p>This is an auto reminder mail for below mentioned incomplete meeting. Please attend and set the status as complete.</p>
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
            <td><a href="{{ url('/') }}" class="btn btn-warning">System Link</a></td>
            <td></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>