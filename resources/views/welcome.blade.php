<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AJAX CRUD</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>
    <script src="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="antialiased">
    <div class="container pt-3">
        <h3 style="color: red;">Ajax Crud</h3>
        <button type="button" class="btn btn-primary" id="add_todo"> Add Todo </button>
        <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
            <thead class="bg-dark text-white">
                <th>Sr.no</th>
                <th>Name</th>
                <th>Action</th>
            </thead>
            <tbody id="list_todo">
                @foreach ($todos as $todo)
                    <tr id="row_todo_{{ $todo->id }}">
                        <td width="20">{{ $loop->iteration }}</td>
                        <td>{{ $todo->name }}</td>
                        <td width="150">
                            <button type="button" id="edit_todo" data-id="{{ $todo->id }}"
                                class="btn btn-sm btn-info ml-1">Edit</button>

                            <button type="button" id="delete_todo" data-id="{{ $todo->id }}"
                                class="btn btn-sm btn-danger ml-1">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- The Modal -->
    <div class="modal" id="modal_todo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_todo">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="name" id="name_todo" class="form-control"
                            placeholder="Enter Name ...">

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });



        $("#add_todo").on('click', function() {
            $("#form_todo").trigger('reset');
            $("#modal_title").html('Add todo');
            $("#modal_todo").modal('show');
            $("#id").val("");
        });

        $("body").on('click', '#edit_todo', function() {
            var id = $(this).data('id');
            $.get('todos/' + id + '/edit', function(res) {
                $("#modal_title").html('Edit Todo');
                $("#id").val(res.id);
                $("#name_todo").val(res.name);
                $("#modal_todo").modal('show');
            });
        });

        // Delete Todo 
        $("body").on('click', '#delete_todo', function() {
            var id = $(this).data('id');
            confirm('Are you sure want to delete !');

            $.ajax({
                type: 'DELETE',
                url: "todos/destroy/" + id
            }).done(function(res) {
                $("#row_todo_" + id).remove();
            });
        });

        //save data 

        $("form").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "todos/store",
                data: $("#form_todo").serialize(),
                type: 'POST'
            }).done(function(res) {
                var row = '<tr id="row_todo_' + res.id + '">';
                row += '<td width="20">' + res.id + '</td>';
                row += '<td>' + res.name + '</td>';
                row += '<td width="150">' + '<button type="button" id="edit_todo" data-id="' + res.id +
                    '" class="btn btn-info btn-sm mr-1">Edit</button>' +
                    '<button type="button" id="delete_todo" data-id="' + res.id +
                    '" class="btn btn-danger btn-sm">Delete</button>' + '</td>';


                if ($("#id").val()) {
                    $("#row_todo_" + res.id).replaceWith(row);
                } else {
                    $("#list_todo").prepend(row);
                }

                $("#form_todo").trigger('reset');
                $("#modal_todo").modal('hide');

            });
        });

        new DataTable('#example', {
            ajax: '../data/2500.txt',
            deferRender: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: 200
        });
    </script>
</body>

</html>
