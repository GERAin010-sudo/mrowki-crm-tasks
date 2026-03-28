## Изменения в существующих файлах

Эти файлы уже существуют в проекте. Нужно внести изменения вручную.

---

### 1. `modules_statuses.json` (бэкенд, корень проекта)

Добавить строку `"Task": true` в конец JSON:

```diff
  "ApiAccess": true,
- "Statistics": true
+ "Statistics": true,
+ "Task": true
 }
```

---

### 2. `src/config/query-keys.config.ts` (фронтенд)

Добавить перед закрывающей `};` два блока:

```diff
   PEOPLE: {
     GET: 'get-people',
     ...
     SEARCH: 'search-people',
   },
+  TASK: {
+    GET: 'get-tasks',
+    GET_ALL: 'get-tasks-all',
+    GET_ONE: 'get-task',
+    CREATE: 'create-task',
+    UPDATE: 'update-task',
+    DELETE: 'delete-task',
+    MOVE: 'move-task',
+    SEARCH: 'search-tasks',
+    DASHBOARD: 'get-tasks-dashboard',
+  },
+  TASK_PROJECT: {
+    GET: 'get-task-projects',
+    GET_ONE: 'get-task-project',
+    CREATE: 'create-task-project',
+    UPDATE: 'update-task-project',
+    DELETE: 'delete-task-project',
+  },
 };
```
