import { createApp } from 'vue';
import App from './App.vue';
import $ from 'jquery'; // jQuery をインポート

createApp(App).mount('#app');
window.$ = $;  // jQuery をグローバル変数として window に割り当て
