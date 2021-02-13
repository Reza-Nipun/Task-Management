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
<p>Following task has been rescheduled.</p>
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
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>