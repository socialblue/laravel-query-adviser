import { createApp } from 'vue';
import router from "./routes/web";
import layoutMain from "./layouts/main.vue";

const app = createApp(layoutMain);
app.use(router);
app.mount('#app')

export default app;
