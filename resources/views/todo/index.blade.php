@extends('layouts.app')
@section('content')
    <div class="text-center h-100 ">
    <div class="container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
        </header>
        <main role="main" class="inner cover">
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title">Todo List</h5>
               </div>
               <div class="card-body">
                   <form id="todoForm">
                       <div class="form-group">
                           <label for="todoInput">Today I Want to</label>
                           <input type="text" class="form-control" id="todoInput">
                       </div>
                       <div class="form-group">
                           <button type="submit" class="btn btn-primary">Tambahkan</button>
                       </div>
                   </form>
                   <ul class="list-group" id="todoList">
                       @foreach ($todo as $data)
                       <li data="{{$data->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                           <span></span>
                           <span class="todoText {{($data->status == 1) ?: 'strike'}}"> {{$data->todoName}}</span>
                           <button value="{{$data->id}}" class="todo-delete btn btn-primary">Delete</button>
                       </li>
                       @endforeach
                   </ul>
               </div>
           </div>
        </main>
        <footer class="mt-auto">
        </footer>
    </div>
    </div>
@endsection
@section('css')
    <style>
        .strike {
            text-decoration: line-through;
        }
    </style>
@endsection
@section('js')
    <script>
        var list, todoForm;
        list = $('#todoList');
        todoForm = $('#todoForm');
        $(function() {
            console.log(todoForm)
            todoForm.on('submit', function(e) {
                e.preventDefault();
                var text = $('input:text').val();
                let data = {
                    'todoName': text
                }
                add(text)
            });
            list.on('click', '.todo-delete', function(e) {
                var item = $(this);
                deleteTodo(item.val())
                $(item).parent().remove();
                e.stopPropagation();
            });
            list.on('click', 'li', function(e) {
                let id = $(this).attr('data')
                updateStatus(id)
                $(this).children('.todoText').toggleClass("strike");
            });

        });
        function deleteTodoItem(e, item) {

        }
        function generatelist(text,id){
            return '<li data="'+id+'" class="list-group-item d-flex justify-content-between align-items-center"><span />' +
                '<span class="todoText"> ' + text + '</span>' +
                '<button value="'+id+'" class="todo-delete btn btn-primary">Delete</button></li>'
        }
        function add(text) {
            console.log(list)
            $.ajax({
                type: 'POST',
                url: '/store',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    todoName: text
                },
                success: function (result) {
                    list.append(generatelist(text,result));
                    $('input:text').val('');
                },
                error: function (error) {
                    alert(error.responseJSON.message);
                }
            });
        }
        function updateStatus(id) {
            console.log(list)
            $.ajax({
                type: 'PUT',
                url: '/'+id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    return result;
                },
                error: function (error) {
                    alert(error.responseJSON.message);
                    return false
                }
            });
        }
        function deleteTodo(id) {
            console.log(list)
            $.ajax({
                type: 'DELETE',
                url: '/'+id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }
    </script>
@endsection
