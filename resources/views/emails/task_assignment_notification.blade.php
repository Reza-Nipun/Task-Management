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
<p>You have received new task, which is mentioned below.</p>
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
            <th>Remarks</th>
            <td>{{ $remarks }}</td>
        </tr>
        <tr>
            <td><a href="{{ url('/reschedule_task_from_email/'.$task_id) }}" class="btn btn-warning">Reschedule Task Here</a></td>
            <td></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>