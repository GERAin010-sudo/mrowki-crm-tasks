<?php

namespace Modules\Task\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskCategory;
use Modules\Task\Models\TaskComment;
use Modules\Task\Models\TaskPriority;
use Modules\Task\Models\TaskProject;
use Modules\Task\Models\TaskStatus;
use Modules\Task\Models\TaskSubtask;
use Modules\Task\Models\TaskTag;
use Modules\Task\Models\TaskTemplate;
use Modules\Task\Models\TaskTimeEntry;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // ── Statuses ──
        $statuses = [
            ['name' => 'new',         'label' => 'Новая',        'color' => '#6b7280', 'bg' => '#f3f4f6', 'position' => 1],
            ['name' => 'in_progress', 'label' => 'В работе',     'color' => '#2563eb', 'bg' => '#dbeafe', 'position' => 2],
            ['name' => 'review',      'label' => 'На проверке',  'color' => '#d97706', 'bg' => '#fef3c7', 'position' => 3],
            ['name' => 'done',        'label' => 'Выполнена',    'color' => '#059669', 'bg' => '#d1fae5', 'position' => 4],
        ];
        foreach ($statuses as $s) {
            TaskStatus::updateOrCreate(['name' => $s['name']], $s);
        }

        // ── Priorities ──
        $priorities = [
            ['name' => 'low',      'label' => 'Низкий',       'color' => '#6b7280', 'icon' => '↓', 'position' => 1],
            ['name' => 'medium',   'label' => 'Средний',      'color' => '#2563eb', 'icon' => '→', 'position' => 2],
            ['name' => 'high',     'label' => 'Высокий',      'color' => '#d97706', 'icon' => '↑', 'position' => 3],
            ['name' => 'critical', 'label' => 'Критический',  'color' => '#dc2626', 'icon' => '⚡', 'position' => 4],
        ];
        foreach ($priorities as $p) {
            TaskPriority::updateOrCreate(['name' => $p['name']], $p);
        }

        // ── Categories ──
        $categories = [
            ['name' => 'hr',          'label' => 'HR',            'color' => '#7c3aed'],
            ['name' => 'sales',       'label' => 'Продажи',       'color' => '#2563eb'],
            ['name' => 'recruitment', 'label' => 'Рекрутинг',     'color' => '#059669'],
            ['name' => 'admin',       'label' => 'Администрация', 'color' => '#dc2626'],
            ['name' => 'logistics',   'label' => 'Логистика',     'color' => '#d97706'],
            ['name' => 'finance',     'label' => 'Финансы',       'color' => '#0891b2'],
            ['name' => 'it',          'label' => 'IT',            'color' => '#4f46e5'],
            ['name' => 'other',       'label' => 'Другое',        'color' => '#6b7280'],
        ];
        foreach ($categories as $c) {
            TaskCategory::updateOrCreate(['name' => $c['name']], $c);
        }

        // ── Tags ──
        $tags = [
            ['label' => 'срочно',       'color' => '#dc2626'],
            ['label' => 'документы',    'color' => '#2563eb'],
            ['label' => 'рекрутинг',    'color' => '#7c3aed'],
            ['label' => 'onboarding',   'color' => '#059669'],
            ['label' => 'финансы',      'color' => '#d97706'],
            ['label' => 'коммуникация', 'color' => '#0891b2'],
            ['label' => 'проверка',     'color' => '#be185d'],
            ['label' => 'ожидание',     'color' => '#6b7280'],
        ];
        foreach ($tags as $t) {
            TaskTag::updateOrCreate(['label' => $t['label']], $t);
        }

        // ── Templates ──
        TaskTemplate::updateOrCreate(
            ['name' => '👤 Новый работник'],
            [
                'description' => 'Набор задач для оформления нового работника на подряд',
                'icon'        => '👤',
                'color'       => '#059669',
                'tasks_json'  => [
                    ['title' => 'Получить документы от работника', 'category' => 'hr', 'priority' => 'high'],
                    ['title' => 'Проверить разрешение на работу', 'category' => 'hr', 'priority' => 'critical'],
                    ['title' => 'Оформить договор / umowa', 'category' => 'hr', 'priority' => 'high'],
                    ['title' => 'Добавить работника в систему', 'category' => 'admin', 'priority' => 'medium'],
                    ['title' => 'Провести инструктаж по безопасности', 'category' => 'logistics', 'priority' => 'high'],
                    ['title' => 'Организовать транспорт на объект', 'category' => 'logistics', 'priority' => 'medium'],
                ],
            ]
        );

        TaskTemplate::updateOrCreate(
            ['name' => '📋 Новый подряд'],
            [
                'description' => 'Задачи при запуске нового подряда с работодателем',
                'icon'        => '📋',
                'color'       => '#2563eb',
                'tasks_json'  => [
                    ['title' => 'Подготовить и подписать NDA', 'category' => 'sales', 'priority' => 'high'],
                    ['title' => 'Составить и согласовать контракт', 'category' => 'sales', 'priority' => 'critical'],
                    ['title' => 'Определить ставки и условия', 'category' => 'sales', 'priority' => 'high'],
                    ['title' => 'Назначить координатора', 'category' => 'admin', 'priority' => 'medium'],
                    ['title' => 'Начать рекрутинг на вакансии', 'category' => 'recruitment', 'priority' => 'high'],
                ],
            ]
        );

        TaskTemplate::updateOrCreate(
            ['name' => '🔍 Рекрутинг на вакансию'],
            [
                'description' => 'Стандартный процесс набора людей на вакансию',
                'icon'        => '🔍',
                'color'       => '#7c3aed',
                'tasks_json'  => [
                    ['title' => 'Разместить вакансию на площадках', 'category' => 'recruitment', 'priority' => 'high'],
                    ['title' => 'Провести первичный отбор резюме', 'category' => 'recruitment', 'priority' => 'medium'],
                    ['title' => 'Провести собеседования', 'category' => 'recruitment', 'priority' => 'medium'],
                    ['title' => 'Отправить оффер кандидатам', 'category' => 'recruitment', 'priority' => 'high'],
                ],
            ]
        );

        // ── Sample Projects ──
        $proj1 = TaskProject::updateOrCreate(
            ['name' => 'Подряд INSTALCOMPACT — электрики'],
            [
                'description'     => 'Постоянный подряд. Набор и управление электриками.',
                'status'          => 'active',
                'type'            => 'contract',
                'color'           => '#2563eb',
                'creator_id'      => 1,
                'coordinator_id'  => 2,
                'contractor_name' => 'INSTALCOMPACT Sp. z o.o.',
            ]
        );

        $proj2 = TaskProject::updateOrCreate(
            ['name' => 'Подряд HOTEL KORMORAN — обслуживание'],
            [
                'description'     => 'Постоянный подряд. Официанты, повара, обслуживающий персонал.',
                'status'          => 'active',
                'type'            => 'contract',
                'color'           => '#059669',
                'creator_id'      => 1,
                'coordinator_id'  => 4,
                'contractor_name' => 'HOTEL KORMORAN Resort & Spa',
            ]
        );

        // ── Get lookup IDs ──
        $statusNew = TaskStatus::where('name', 'new')->first()->id;
        $statusInProg = TaskStatus::where('name', 'in_progress')->first()->id;
        $priHigh = TaskPriority::where('name', 'high')->first()->id;
        $priMedium = TaskPriority::where('name', 'medium')->first()->id;
        $priCritical = TaskPriority::where('name', 'critical')->first()->id;
        $catSales = TaskCategory::where('name', 'sales')->first()->id;
        $catRecruit = TaskCategory::where('name', 'recruitment')->first()->id;
        $catHR = TaskCategory::where('name', 'hr')->first()->id;

        // ── Sample Tasks ──
        $task1 = Task::updateOrCreate(
            ['title' => 'Подготовить документы для нового контрагента Delta Sp.J.'],
            [
                'description'   => 'Необходимо подготовить полный пакет документов для заключения договора с контрагентом Delta Sp.J.',
                'status_id'     => $statusInProg,
                'priority_id'   => $priHigh,
                'category_id'   => $catSales,
                'project_id'    => $proj1->id,
                'creator_id'    => 1,
                'assignee_type' => 'team',
                'deadline'      => '2026-03-28 17:00:00',
            ]
        );

        // Subtasks
        TaskSubtask::updateOrCreate(['task_id' => $task1->id, 'text' => 'Подготовить NDA'], ['is_done' => true, 'position' => 1]);
        TaskSubtask::updateOrCreate(['task_id' => $task1->id, 'text' => 'Составить основной договор'], ['is_done' => true, 'position' => 2]);
        TaskSubtask::updateOrCreate(['task_id' => $task1->id, 'text' => 'Подготовить приложения к договору'], ['is_done' => false, 'position' => 3]);
        TaskSubtask::updateOrCreate(['task_id' => $task1->id, 'text' => 'Согласовать с юристом'], ['is_done' => false, 'position' => 4]);

        // Comments
        TaskComment::updateOrCreate(
            ['task_id' => $task1->id, 'text' => 'Прошу подготовить до конца недели'],
            ['user_id' => 1, 'created_at' => '2026-03-20 10:05:00']
        );

        // Time entries
        TaskTimeEntry::updateOrCreate(
            ['task_id' => $task1->id, 'description' => 'Подготовка пакета документов'],
            ['user_id' => 2, 'minutes' => 120, 'date' => '2026-03-21']
        );

        $task2 = Task::updateOrCreate(
            ['title' => 'Разместить вакансию "Електрик" на 3 площадках'],
            [
                'description' => 'Опубликовать вакансию электрика на OLX, Pracuj.pl и Indeed.',
                'status_id'   => $statusNew,
                'priority_id' => $priMedium,
                'category_id' => $catRecruit,
                'project_id'  => $proj1->id,
                'creator_id'  => 1,
                'assignee_id' => 3,
                'deadline'    => '2026-03-27',
            ]
        );

        $task3 = Task::updateOrCreate(
            ['title' => 'Провести собеседования с кандидатами на "Офіціант"'],
            [
                'description' => 'Запланировать и провести собеседования с 5 кандидатами.',
                'status_id'   => $statusInProg,
                'priority_id' => $priHigh,
                'category_id' => $catHR,
                'project_id'  => $proj2->id,
                'creator_id'  => 1,
                'assignee_id' => 3,
                'deadline'    => '2026-03-29 16:00:00',
            ]
        );

        $task4 = Task::updateOrCreate(
            ['title' => 'Обновить зарплатную ведомость за март'],
            [
                'description' => 'Подготовить и обновить зарплатную ведомость для всех работников за март 2026.',
                'status_id'   => $statusNew,
                'priority_id' => $priCritical,
                'category_id' => TaskCategory::where('name', 'finance')->first()->id,
                'creator_id'  => 1,
                'assignee_id' => 5,
                'deadline'    => '2026-03-31 12:00:00',
            ]
        );

        // ── Attach tags to task1 ──
        $tagDocs = TaskTag::where('label', 'документы')->first();
        $tagUrgent = TaskTag::where('label', 'срочно')->first();
        if ($tagDocs && $tagUrgent) {
            $task1->tags()->syncWithoutDetaching([$tagDocs->id, $tagUrgent->id]);
        }

        $this->command?->info('✅ Task module seeded successfully');
    }
}
