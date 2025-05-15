<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel To-Do List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            color: #34495e;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Shortened Input and Button Row */
        .input-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 90%; /* Reduced width for compactness */
            margin-left: auto;
            margin-right: auto;
        }

        #taskInput {
            width: 70%; /* Shortened width */
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 1rem;
            margin-right: 10px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        #taskInput:focus {
            border-color: #3498db;
            outline: none;
        }

        #addTaskBtn {
            background-color: #28a745; /* Green Button */
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            width: 25%; /* Shortened width */
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        #addTaskBtn:hover {
            background-color: #218838; /* Darker green on hover */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Filter Buttons */
        .filter-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-buttons button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .filter-buttons button:hover {
            background-color: #2980b9;
        }

        .filter-buttons button.active {
            background-color: #2c3e50;
        }

        /* Task List Styling */
        #taskList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #taskList li {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        #taskList li:hover {
            background-color: #f1f1f1;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #taskList li span {
            flex-grow: 1;
            font-size: 1rem;
        }

        .completed {
            text-decoration: line-through;
            color: #888;
        }

        .deleteBtn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .deleteBtn:hover {
            background-color: #c0392b;
        }

        .task-row {
            display: flex;
            align-items: center;
        }

        /* No tasks message */
        .no-tasks {
            text-align: center;
            color: #7f8c8d;
            margin-top: 20px;
        }

        .completed-message {
            text-align: center;
            color: #7f8c8d;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>To-Do List</h1>

        <!-- Add Task Form in Same Row -->
        <div class="input-row">
            <input type="text" id="taskInput" placeholder="Enter a task">
            <button id="addTaskBtn">Add Task</button>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button id="showAllBtn" class="active">All Tasks</button>
            <button id="showActiveBtn">Active Tasks</button>
            <button id="showCompletedBtn">Completed Tasks</button>
        </div>

        <!-- Task List -->
        <ul id="taskList"></ul>
    </div>

    <script>
        $(document).ready(function() {
            let currentFilter = 'all';  // Default filter: 'all', 'active', 'completed'

            // Function to fetch and display tasks based on filter
            function fetchTasks(filter = currentFilter) {
                let url = '/tasks';
                if (filter === 'active') {
                    url = '/tasks/active';
                } else if (filter === 'completed') {
                    url = '/tasks/completed';
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(tasks) {
                        renderTasks(tasks);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching tasks:", error);
                    }
                });
            }

            // Render tasks on the page
            function renderTasks(tasks) {
                $('#taskList').empty();
                if (tasks.length === 0) {
                    $('#taskList').append('<li class="no-tasks">No tasks found</li>');
                } else {
                    tasks.forEach(function(task) {
                        $('#taskList').append(`
                            <li id="task-${task.id}" class="${task.completed ? 'completed' : ''}">
                                <div class="task-row">
                                    <input type="checkbox" class="completeTask" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                                    <span>${task.task}</span>
                                </div>
                                <button class="deleteBtn" data-id="${task.id}">Delete</button>
                            </li>
                        `);
                    });
                }
            }

            // Initially load all tasks
            fetchTasks();

            // Add Task on button click or Enter key press
            function addTask() {
                var task = $('#taskInput').val().trim();

                if (task === "") {
                    alert("Task cannot be empty!");
                    return;
                }

                $.ajax({
                    url: '/task',
                    method: 'POST',
                    data: {
                        task: task,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#taskInput').val('');
                        fetchTasks(currentFilter);
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            alert(response.responseJSON.errors.task[0]);
                        } else {
                            alert("Error adding task");
                        }
                    }
                });
            }

            $('#addTaskBtn').on('click', addTask);
            $('#taskInput').on('keypress', function(e) {
                if (e.which === 13) addTask();
            });

            // Toggle task completion and remove from frontend
            $(document).on('change', '.completeTask', function() {
                var id = $(this).data('id');
                var completed = $(this).is(':checked') ? 1 : 0;
                var $taskItem = $(this).closest('li');

                // Send the updated status to the backend via AJAX
                $.ajax({
                    url: '/task/' + id,
                    method: 'PUT',
                    data: {
                        completed: completed,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // If the task is completed, remove it from the frontend immediately
                        if (completed) {
                            $taskItem.fadeOut(300, function() {
                                $(this).remove();
                                if ($('#taskList li').not('.no-tasks').length === 0) {
                                    $('#taskList').append('<li class="no-tasks">No active tasks</li>');
                                }
                            });
                        }
                    },
                    error: function() {
                        $(this).prop('checked', !completed);  // Revert checkbox state
                        alert('Failed to update task status');
                    }
                });
            });

            // Delete task with confirmation
            $(document).on('click', '.deleteBtn', function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    var id = $(this).data('id');
                    var $taskItem = $(this).closest('li');

                    $.ajax({
                        url: '/task/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            $taskItem.fadeOut(300, function() {
                                $(this).remove();
                                if ($('#taskList li').not('.no-tasks').length === 0) {
                                    $('#taskList').append('<li class="no-tasks">No tasks found</li>');
                                }
                            });
                        }
                    });
                }
            });

            // Filter buttons
            $('#showAllBtn').on('click', function() {
                currentFilter = 'all';
                fetchTasks();
                updateFilterActiveButton();
            });

            $('#showActiveBtn').on('click', function() {
                currentFilter = 'active';
                fetchTasks();
                updateFilterActiveButton();
            });

            $('#showCompletedBtn').on('click', function() {
                currentFilter = 'completed';
                fetchTasks();
                updateFilterActiveButton();
            });

            // Update active button styles
            function updateFilterActiveButton() {
                $('.filter-buttons button').removeClass('active');
                if (currentFilter === 'all') {
                    $('#showAllBtn').addClass('active');
                } else if (currentFilter === 'active') {
                    $('#showActiveBtn').addClass('active');
                } else if (currentFilter === 'completed') {
                    $('#showCompletedBtn').addClass('active');
                }
            }
        });
    </script>
</body>
</html>
