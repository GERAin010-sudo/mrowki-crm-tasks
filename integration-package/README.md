# 🐜 Mrówki CRM — Модуль «Задачи» (Task Module)

## Что здесь

Готовый модуль управления задачами для интеграции в CRM Mrówki group.
Все файлы сделаны **по паттернам существующего кода** (по аналогии с `RecruitmentTask`).

```
📦 Этот репозиторий
├── 📁 backend/Modules/Task/        ← Скопировать в Modules/ бэкенда
├── 📁 frontend/services/task/      ← Скопировать в src/services/ фронтенда  
├── 📁 patches/                     ← Изменения в существующие файлы
└── 📄 README.md                    ← Эта инструкция
```

---

## ⚡ Быстрый старт (5 шагов)

### 1. Скопировать бэкенд-модуль

Скопировать всю папку `backend/Modules/Task/` в бэкенд-проект:

```bash
cp -r backend/Modules/Task /path/to/crm-backend/Modules/
```

### 2. Активировать модуль

В файле `modules_statuses.json` добавить:

```diff
  "Statistics": true,
+ "Task": true
```

### 3. Запустить миграции и сиды

```bash
docker compose exec application php artisan migrate
docker compose exec application php artisan db:seed --class="Modules\\Task\\Database\\Seeders\\TaskSeeder"
```

### 4. Скопировать фронтенд-сервисы

```bash
cp -r frontend/services/task /path/to/crm-frontend/src/services/
```

### 5. Обновить `query-keys.config.ts`

Добавить в конец объекта `QUERY_KEYS` (перед закрывающей `}`):

```typescript
  TASK: {
    GET: 'get-tasks',
    GET_ALL: 'get-tasks-all',
    GET_ONE: 'get-task',
    CREATE: 'create-task',
    UPDATE: 'update-task',
    DELETE: 'delete-task',
    MOVE: 'move-task',
    SEARCH: 'search-tasks',
    DASHBOARD: 'get-tasks-dashboard',
  },
  TASK_PROJECT: {
    GET: 'get-task-projects',
    GET_ONE: 'get-task-project',
    CREATE: 'create-task-project',
    UPDATE: 'update-task-project',
    DELETE: 'delete-task-project',
  },
```

---

## ✅ Проверка работоспособности

```bash
# 1. Проверить что таблицы созданы
docker compose exec application php artisan tinker --execute="echo Schema::hasTable('tasks') ? 'OK' : 'FAIL';"

# 2. Проверить API (подставить свой токен)
curl http://localhost:8080/api/v1/tasks -H "Authorization: Bearer {token}" -H "Accept: application/json"

# 3. Ожидаемый результат — JSON с задачами из сида
```

---

## 📊 Что создаётся в базе данных

### 13 таблиц:

| Таблица | Описание |
|---------|----------|
| `task_statuses` | Новая / В работе / На проверке / Выполнена |
| `task_priorities` | Низкий / Средний / Высокий / Критический |
| `task_categories` | HR, Продажи, Рекрутинг, IT и т.д. (8 шт) |
| `task_projects` | Проекты (привязка к coordinator, contragent) |
| `tasks` | **Главная таблица** — задачи со всеми FK |
| `task_assignees` | Pivot: команда исполнителей |
| `task_watchers` | Pivot: наблюдатели |
| `task_subtasks` | Подзадачи с чекбоксами |
| `task_comments` | Комментарии |
| `task_tags` + `task_tag_task` | Теги (pivot) |
| `task_relations` | Связи: блокирует / связана / дубликат |
| `task_time_entries` | Учёт времени |
| `task_history` | Автоматический аудит изменений |
| `task_templates` | Шаблоны задач (JSON) |

### Ключевые FK в таблице `tasks`:

```
tasks.status_id      → task_statuses.id
tasks.priority_id    → task_priorities.id
tasks.category_id    → task_categories.id
tasks.project_id     → task_projects.id
tasks.creator_id     → users.id
tasks.assignee_id    → users.id
tasks.contragent_id  → contragents.id       ← привязка к сущности!
```

---

## 🔌 API Endpoints

Все под `auth:sanctum`, prefix `/api/v1`:

### Задачи (CRUD + Kanban)
```
GET    /v1/tasks                  — список с фильтрами
GET    /v1/tasks/kanban           — канбан-вид
GET    /v1/tasks/dashboard        — статистика (total, in_progress, overdue, done)
POST   /v1/tasks                  — создать
GET    /v1/tasks/{id}             — детали (со всеми связями)
PUT    /v1/tasks/{id}             — обновить
DELETE /v1/tasks/{id}             — удалить
POST   /v1/tasks/move             — перемещение (drag-n-drop)
```

### Вложенные ресурсы
```
POST   /v1/tasks/{id}/subtasks           — добавить подзадачу
PUT    /v1/tasks/{id}/subtasks/{sid}     — toggle / edit
DELETE /v1/tasks/{id}/subtasks/{sid}     — удалить
GET    /v1/tasks/{id}/comments           — комментарии
POST   /v1/tasks/{id}/comments           — добавить
GET    /v1/tasks/{id}/time-entries       — записи времени
POST   /v1/tasks/{id}/time-entries       — залогировать
```

### Проекты
```
GET    /v1/task-projects           — список
POST   /v1/task-projects           — создать
GET    /v1/task-projects/{id}      — детали
PUT    /v1/task-projects/{id}      — обновить
DELETE /v1/task-projects/{id}      — удалить
```

### Справочники
```
GET    /v1/task-statuses
GET    /v1/task-priorities
GET    /v1/task-categories
GET    /v1/task-tags
GET    /v1/task-templates
POST   /v1/task-templates/{id}/apply    — создать задачи из шаблона
```

### Фильтры для `GET /v1/tasks`
```
?status_id=1&priority_id=2&category_id=3&project_id=5
?assignee_id=1&creator_id=1&contragent_id=10
?search=документы
?deadline_from=2026-03-01&deadline_to=2026-04-01
?sort=deadline&dir=asc
?page=1&per_page=10
```

---

## 📁 Структура бэкенд-модуля

```
Modules/Task/
├── module.json
├── composer.json
├── README.md
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TaskController.php          ← CRUD + kanban + dashboard
│   │   │   ├── TaskProjectController.php
│   │   │   ├── TaskCommentController.php
│   │   │   ├── TaskSubtaskController.php
│   │   │   └── TaskTimeEntryController.php
│   │   ├── Requests/Task/
│   │   │   ├── IndexRequest.php
│   │   │   ├── StoreRequest.php
│   │   │   ├── UpdateRequest.php
│   │   │   └── MoveRequest.php
│   │   └── Resources/
│   │       ├── TaskResource.php
│   │       ├── TaskShortResource.php
│   │       ├── TaskKanbanCollection.php
│   │       └── TaskProjectResource.php
│   ├── Models/
│   │   ├── Task.php                        ← главная модель (12 relations)
│   │   ├── TaskProject.php
│   │   ├── TaskStatus.php
│   │   ├── TaskPriority.php
│   │   ├── TaskCategory.php
│   │   ├── TaskSubtask.php
│   │   ├── TaskComment.php
│   │   ├── TaskTag.php
│   │   ├── TaskRelation.php
│   │   ├── TaskTimeEntry.php
│   │   ├── TaskHistory.php
│   │   └── TaskTemplate.php
│   ├── Observers/
│   │   └── TaskObserver.php                ← авто-запись изменений
│   ├── Providers/
│   │   ├── TaskServiceProvider.php
│   │   ├── RouteServiceProvider.php
│   │   └── EventServiceProvider.php
│   └── Services/
│       ├── TaskService.php                 ← extends AbstractCRUDService
│       └── TaskProjectService.php
├── database/
│   ├── migrations/                         ← 13 миграций
│   └── seeders/
│       └── TaskSeeder.php                  ← тестовые данные
└── routes/
    └── api.php                             ← 20+ endpoints
```

---

## 📁 Структура фронтенд-сервисов

```
src/services/task/
├── task.types.ts        ← TypeScript интерфейсы (~200 строк)
├── task.service.ts      ← axios API вызовы (все endpoints)
└── useTask.hook.tsx     ← React Query хуки (по аналогии с useTaskRecruitment)
```

---

## 🚀 Что осталось реализовать на фронтенде

Сервисы и хуки готовы. Нужны **страницы**:

```
app/[locale]/(dashboard)/tasks/page.tsx          ← список задач
app/[locale]/(dashboard)/tasks/kanban/page.tsx   ← канбан-доска
app/[locale]/(dashboard)/tasks/[id]/page.tsx     ← детали задачи
app/[locale]/(dashboard)/task-projects/page.tsx  ← проекты
```

Использование:
```tsx
import { useGetTasks, useCreateTask, useDeleteTask } from '@/services/task/useTask.hook';

const { data: tasks, isLoading } = useGetTasks({ status_id: 1, project_id: 5 });
const createTask = useCreateTask();
const deleteTask = useDeleteTask();
```

Также нужно:
- Добавить пункт меню «Задачи» в sidebar
- Добавить i18n переводы для toast-уведомлений

---

## ⚠️ Не реализовано (TODO)

- **Уведомления** (push / telegram / email) — отложено
- **Файлы/вложения** — использовать существующий модуль `Media`
