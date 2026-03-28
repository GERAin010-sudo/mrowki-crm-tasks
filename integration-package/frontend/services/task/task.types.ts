// =============================================
// Task Module — TypeScript Types
// Mirrors the Laravel API responses
// =============================================

// ── Lookup types ──

export interface ITaskStatus {
  id: number;
  name: string;
  label: string;
  color: string;
  bg: string;
  position: number;
}

export interface ITaskPriority {
  id: number;
  name: string;
  label: string;
  color: string;
  icon: string;
  position: number;
}

export interface ITaskCategory {
  id: number;
  name: string;
  label: string;
  color: string;
}

export interface ITaskTag {
  id: number;
  label: string;
  color: string;
}

// ── Task Project ──

export interface ITaskProject {
  id: number;
  name: string;
  description: string | null;
  status: 'active' | 'planned' | 'completed' | 'archived';
  type: 'contract' | 'one_time' | 'internal';
  color: string;
  creator?: any;
  coordinator?: any;
  contragent?: any;
  contractor_name: string | null;
  tasks_count?: number;
  created_at: string;
  updated_at: string;
}

// ── Task (short — for lists) ──

export interface ITaskShort {
  id: number;
  title: string;
  status: ITaskStatus | null;
  priority: ITaskPriority | null;
  category: ITaskCategory | null;
  project: { id: number; name: string; color: string } | null;
  assignee: any | null;
  contragent: { id: number; name: string } | null;
  assignee_type: 'user' | 'team' | 'department';
  deadline: string | null;
  created_at: string;
}

// ── Task (full — for detail page) ──

export interface ITaskFull extends ITaskShort {
  description: string | null;
  creator: any | null;
  assignees: any[];
  watchers: any[];
  subtasks: ITaskSubtask[];
  comments: ITaskComment[];
  tags: ITaskTag[];
  relations: ITaskRelation[];
  time_entries: ITaskTimeEntry[];
  history: ITaskHistoryEntry[];
  linked_entity_type: string | null;
  linked_entity_id: number | null;
  linked_entity_name: string | null;
  updated_at: string;
}

export interface ITaskSubtask {
  id: number;
  task_id: number;
  text: string;
  is_done: boolean;
  position: number;
}

export interface ITaskComment {
  id: number;
  task_id: number;
  user_id: number;
  user?: any;
  text: string;
  created_at: string;
}

export interface ITaskRelation {
  id: number;
  task_id: number;
  related_task_id: number;
  type: 'blocks' | 'blocked_by' | 'related' | 'duplicate';
  related_task?: ITaskShort;
}

export interface ITaskTimeEntry {
  id: number;
  task_id: number;
  user_id: number;
  user?: any;
  minutes: number;
  description: string | null;
  date: string;
}

export interface ITaskHistoryEntry {
  id: number;
  task_id: number;
  user_id: number;
  user?: any;
  field: string;
  old_value: string | null;
  new_value: string | null;
  created_at: string;
}

// ── Dashboard stats ──

export interface ITaskDashboard {
  total: number;
  in_progress: number;
  overdue: number;
  done: number;
}

// ── Kanban ──

export interface ITaskKanbanColumn {
  status: ITaskStatus;
  tasks: ITaskShort[];
}

// ── Request / Response types ──

export interface IGetTasksRequest {
  status_id?: number;
  priority_id?: number;
  category_id?: number;
  project_id?: number;
  assignee_id?: number;
  creator_id?: number;
  contragent_id?: number;
  search?: string;
  deadline_from?: string;
  deadline_to?: string;
  sort?: string;
  dir?: 'asc' | 'desc';
  page?: number;
  per_page?: number;
  limit?: number;
}

export type IGetTasksResponse = { data: ITaskShort[]; meta?: any };
export type IGetTaskResponse = { data: { data: ITaskFull } };
export type IGetTasksKanbanResponse = { data: ITaskKanbanColumn[] };
export type IGetTaskDashboardResponse = { data: ITaskDashboard };

export interface ICreateTaskRequest {
  title: string;
  description?: string;
  status_id?: number;
  priority_id?: number;
  category_id?: number;
  project_id?: number;
  assignee_id?: number;
  assignee_type?: string;
  contragent_id?: number;
  deadline?: string;
  assignee_ids?: number[];
  tag_ids?: number[];
}

export type ICreateTaskResponse = { data: { data: ITaskFull } };

export interface IUpdateTaskRequest {
  id: number;
  data: Partial<ICreateTaskRequest>;
}

export type IUpdateTaskResponse = ICreateTaskResponse;

export interface IDeleteTaskRequest {
  id: number;
}

export type IDeleteTaskResponse = { data: { data: ITaskFull } };

export interface IMoveTaskRequest {
  task_id: number;
  prev_id?: number | null;
  next_id?: number | null;
  status_id: number;
}

export type IMoveTaskResponse = Record<string, never>;

// ── Project requests ──

export interface IGetTaskProjectsRequest {
  page?: number;
  per_page?: number;
}

export type IGetTaskProjectsResponse = { data: ITaskProject[]; meta?: any };
export type IGetTaskProjectResponse = { data: { data: ITaskProject } };

export interface ICreateTaskProjectRequest {
  name: string;
  description?: string;
  status?: string;
  type?: string;
  color?: string;
  coordinator_id?: number;
  contragent_id?: number;
  contractor_name?: string;
}

export type ICreateTaskProjectResponse = { data: { data: ITaskProject } };
