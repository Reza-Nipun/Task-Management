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
<p>This is a reminder to deliver the below mentioned task. Please <a href="{{ url('/my_tasks') }}" style="background-color: green; color: white;">schedule a meeting</a> to confirm completion of task or if you wish please <a href="{{ url('/reschedule_task_from_email/'.$task_id) }}" style="background-color: yellow;">reschedule your deliver date</a>.</p>
<p style="background-color: orangered; color: white;">Note: If you fail to take either of the above mentioned actions system will automatically reschedule your delivery date to the next day.</p>
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
            <td>{{ $reschedule_delivery_date }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>{{ $remarks }}</td>
        </tr>
        <tr>
            <td><a href="{{ url('/reschedule_task_from_email/'.$task_id) }}" class="button">Reschedule Task Delivery Date</a></td>
            <td><a href="{{ url('/schedule_task_completion_meeting/'.$task_id) }}" class="button_2">Schedule Task Completion Meeting</a></td>
        </tr>
    </thead>
</table>
<br />
<br />
<p>Thank You</p>
</body>
</html>