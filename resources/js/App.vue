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
        </ul>
    </div>
</template>

<script>

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
