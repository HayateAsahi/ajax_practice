<template>
    <div>
        <h1>タスク管理</h1>
        <input
            v-model="newTaskTitle"
            type="text"
            placeholder="新しいタスクを追加"
        />
        <button @click="addTask">タスク追加</button>

        <ul id="task-list">
            <li v-for="task in tasks" :key="task.id">
                <span :class="{ completed: task.completed }">{{
                    task.title
                }}</span>
                <button @click="toggleTask(task)">完了</button>
                <button @click="deleteTask(task.id)">削除</button>
            </li>
        </ul>
    </div>
</template>

<script>
import $ from "jquery";

export default {
    data() {
        return {
            tasks: [],
            newTaskTitle: "",
        };
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {
        fetchTasks() {
            $.get("/api/tasks", (data) => {
                this.tasks = data;
            });
        },
        addTask() {
            if (this.newTaskTitle.trim() === "") return;

            $.post("/api/tasks", { title: this.newTaskTitle }, (task) => {
                this.tasks.push(task);
                this.newTaskTitle = "";
            });
        },
        deleteTask(id) {
            $.ajax({
                url: `/api/tasks/${id}`,
                type: "DELETE",
                success: () => {
                    this.tasks = this.tasks.filter((task) => task.id !== id);
                },
            });
        },
        toggleTask(task) {
            $.ajax({
                url: `/api/tasks/${task.id}`,
                type: "PUT",
                data: { completed: !task.completed },
                success: () => {
                    task.completed = !task.completed;
                },
            });
        },
    },
};
</script>

<style scoped>
.completed {
    text-decoration: line-through;
}
</style>
