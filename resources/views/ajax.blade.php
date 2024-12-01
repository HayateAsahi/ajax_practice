@extends('welcome')

@section('content')
<div>
    <h1>タスク管理</h1>
    <input
            id="key"
            type="text"
            placeholder="新しいタスクを追加"
        />
    <button id="send">タスク追加</button>

    <ul id="task-lists"></ul>

</div>
@endsection

@section('script')


<script>
    // タスク追加ボタンのクリックイベント
    $("#send").on("click", function() {
        const taskTitle = $("#key").val().trim();
        if(taskTitle === '') return;
        // console.log( $("#key").val().trim())

        $.post('/api/tasks', { title: taskTitle }, function(task) {
            appendTask(task);
            $("key").val('');// 入力フィールドをクリア
        });
    });

    // タスクの取得
    function fecTasks() {
        $.get('/api/tasks', function(tasks) {
            console.log(tasks);
            tasks.forEach(task => appendTask(task));
        });
    }

    // タスクのDOM生成と追加
    function appendTask(task) {
        const taskItem = $(`
            <li data-id="${task.id}" class="${task.completed ? 'completed' : ''}">
                <input type="checkbox" class="toggle-task" ${task.completed ? 'checked' : ''} />
                <span>${task.title}</span>
                <button class="delete-task" id="delete-${task.id}">削除</button>
            </li>
        `);

        // 完了状態の切り替え
        taskItem.find('.toggle-task').on("change", function() {
            toggleTask(task.id, $(this).prop('checked'));
        });

        // タスク削除ボタンのクリックイベント
        taskItem.find(`#delete-${task.id}`).on("click", function() {
            deleteTask(task.id, taskItem);
        });


        $("#task-lists").append(taskItem);
    }

    // タスク削除の関数
    function deleteTask(id, taskItem) {
        $.ajax({
            url: `/api/tasks/${id}`,
            type: 'DELETE',
            success: function() {
                taskItem.remove();
            }
        });
    }

    // タスク完了状態の切り替え関数
    function toggleTask(id, completed) {
        $.ajax({
            url: `/api/tasks/${id}`,
            type: 'PUT',
            data: JSON.stringify({ completed: completed }),  // JSON形式でデータを送信
            success: function() {
                $(`li[data-id="${id}"]`).toggleClass('completed');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);  // エラーレスポンスを表示
            }
        });
        console.log(completed);
    }

</script>

<style scoped>
.completed {
    text-decoration: line-through;
}
</style>
@endsection
