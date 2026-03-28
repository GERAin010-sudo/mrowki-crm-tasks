# Модуль «Задачи» (Task) — Инструкция для разработчика

## Обзор

Новый модуль `Task` добавляет систему управления задачами в CRM Mrówki group.
Это **отдельный** модуль от `RecruitmentTask` — он предназначен для общих задач любого отдела (HR, продажи, логистика, IT и т.д.).

Модуль построен **один-в-один** по паттернам существующего `RecruitmentTask`:
- Те же базовые классы (`AbstractCRUDService`, `RankService`)
- Та же структура (Controllers → Services → Models)
- Те же API Resources и Form Requests
- Тот же фронтенд стек (React Query hooks + axios service)

---

## Структура модуля (Backend)

```
Modules/Task/
├── module.json                    ← регистрация модуля
├── composer.json                  ← PSR-4 autoload
├── app/
│   ├── Models/                    ← 12 моделей (Task, TaskProject, TaskStatus, ...)
│   ├── Http/
│   │   ├── Controllers/           ← 5 контроллеров
│   │   ├── Requests/Task/         ← IndexRequest, StoreRequest, UpdateRequest, MoveRequest
│   │   └── Resources/             ← TaskResource, TaskShortResource, TaskKanbanCollection, TaskProjectResource
│   ├── Services/                  ← TaskService, TaskProjectService (extends AbstractCRUDService)
│   ├── Observers/                 ← TaskObserver (авто-запись истории изменений)
│   └── Providers/                 ← TaskServiceProvider, RouteServiceProvider, EventServiceProvider
├── database/
│   ├── migrations/                ← 13 миграций
│   └── seeders/
│       └── TaskSeeder.php         ← тестовые данные из прототипа
└── routes/
    └── api.php                    ← все API endpoints
```

## Структура (Frontend)

```
src/services/task/
├── task.types.ts                  ← TypeScript интерфейсы
├── task.service.ts                ← axios API вызовы
└── useTask.hook.tsx               ← React Query хуки

src/config/query-keys.config.ts    ← добавлены TASK и TASK_PROJECT ключи
```

---

## Установка

### 1. Проверить `modules_statuses.json`

Модуль уже добавлен:
```json
"Task": true
```

### 2. Запустить миграции

```bash
docker compose exec application php artisan migrate
```

Это создаст 13 таблиц:
- `task_statuses`, `task_priorities`, `task_categories`
- `task_projects`
- `tasks` (главная)
- `task_assignees`, `task_watchers` (pivot)
- `task_subtasks`, `task_comments`
- `task_tags`, `task_tag_task` (pivot)
- `task_relations`, `task_time_entries`
- `task_history`, `task_templates`

### 3. Засидить данные

```bash
docker compose exec application php artisan db:seed --class="Modules\\Task\\Database\\Seeders\\TaskSeeder"
```

### 4. Проверить API

```bash
# Получить токен через стандартную аутентификацию
curl -X GET http://localhost:8080/api/v1/tasks \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## API Endpoints

### Задачи
| Метод | URL | Описание |
|-------|-----|----------|
| GET | `/v1/tasks` | Список с фильтрами и пагинацией |
| GET | `/v1/tasks/kanban` | Канбан-вид |
| GET | `/v1/tasks/dashboard` | Статистика |
| POST | `/v1/tasks` | Создать |
| GET | `/v1/tasks/{id}` | Детали (со всеми связями) |
| PUT | `/v1/tasks/{id}` | Обновить |
| DELETE | `/v1/tasks/{id}` | Удалить |
| POST | `/v1/tasks/move` | Канбан перемещение |

### Вложенные ресурсы
| Метод | URL | Описание |
|-------|-----|----------|
| POST | `/v1/tasks/{id}/subtasks` | Добавить подзадачу |
| PUT | `/v1/tasks/{id}/subtasks/{sid}` | Обновить подзадачу |
| DELETE | `/v1/tasks/{id}/subtasks/{sid}` | Удалить подзадачу |
| GET | `/v1/tasks/{id}/comments` | Комментарии |
| POST | `/v1/tasks/{id}/comments` | Добавить комментарий |
| GET | `/v1/tasks/{id}/time-entries` | Записи времени |
| POST | `/v1/tasks/{id}/time-entries` | Залогировать время |

### Проекты
| Метод | URL | Описание |
|-------|-----|----------|
| GET | `/v1/task-projects` | Список проектов |
| POST | `/v1/task-projects` | Создать |
| GET | `/v1/task-projects/{id}` | Детали |
| PUT | `/v1/task-projects/{id}` | Обновить |
| DELETE | `/v1/task-projects/{id}` | Удалить |

### Справочники (read-only)
| URL | Описание |
|-----|----------|
| `/v1/task-statuses` | Статусы |
| `/v1/task-priorities` | Приоритеты |
| `/v1/task-categories` | Категории |
| `/v1/task-tags` | Теги |
| `/v1/task-templates` | Шаблоны |
| `POST /v1/task-templates/{id}/apply` | Применить шаблон |

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

## Ключевые связи в таблице `tasks`

| FK | Таблица | Описание |
|----|---------|----------|
| `status_id` | `task_statuses` | Статус задачи |
| `priority_id` | `task_priorities` | Приоритет |
| `category_id` | `task_categories` | Категория (HR, IT, ...) |
| `project_id` | `task_projects` | Проект |
| `creator_id` | `users` | Кто создал |
| `assignee_id` | `users` | Основной исполнитель |
| `contragent_id` | `contragents` | Привязка к контрагенту |

Для команд → pivot `task_assignees`, для наблюдателей → pivot `task_watchers`.

---

## Что НЕ реализовано (TODO на потом)

1. **Уведомления** (push, telegram, email) — заглушка готова в mockData, но бэкенд не реализован
2. **Файлы** — используется существующий модуль `Media`, дополнительных таблиц нет
3. **Фронтенд-страницы** — сервисы и хуки готовы, нужно создать страницы в `app/[locale]/(dashboard)/tasks/`

---

## Как добавить страницы (TODO для фронтенда)

Нужно создать:
```
app/[locale]/(dashboard)/tasks/page.tsx          ← список задач
app/[locale]/(dashboard)/tasks/kanban/page.tsx   ← канбан
app/[locale]/(dashboard)/task-projects/page.tsx  ← проекты
```

Использовать хуки из `src/services/task/useTask.hook.tsx`:
```tsx
import { useGetTasks, useCreateTask } from '@/services/task/useTask.hook';

// В компоненте:
const { data: tasks, isLoading } = useGetTasks({ status_id: 1 });
const createTask = useCreateTask();
```
