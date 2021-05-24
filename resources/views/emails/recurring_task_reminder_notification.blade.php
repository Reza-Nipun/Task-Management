<html lang="en-US">
<head>
    <meta charset="text/html">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
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
            font-size: 16px;
            font-weight: 700;
        }

        .button_2 {
            background-color: green; /* Green */
            color: #ffffff;
            padding: 5px;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
            font-weight: 700;
        }
    </style>
</head>
<body>
<p>Dear Concern ,</p>
<p>This is a reminder to complete the below mentioned recurring task. </p>

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
            <th>Task Type</th>
            <td>{{ ($recurring_type == 0 ? 'Monthly' : ($recurring_type == 1 ? 'Weekly' : '')) }}</td>
        </tr>
        @if($recurring_type == 1)
            <tr>
                <th>Delivery Day</th>
                <td>{{ $weekly_recurring_day }}</td>
            </tr>
        @endif
        <tr>
            <th>Delivery Date</th>
            <td>{{ $recurring_task_delivery_date }}</td>
        </tr>
        <tr>
            <td><a href="{{ url('/get_my_recurring_tasks/') }}" class="button">Recurring Task List</a></td>
            <td></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>