import { api } from '@/lib/axios';
import {
  ICreateTaskRequest,
  ICreateTaskResponse,
  IDeleteTaskRequest,
  IDeleteTaskResponse,
  IGetTaskDashboardResponse,
  IGetTaskResponse,
  IGetTasksKanbanResponse,
  IGetTasksRequest,
  IGetTasksResponse,
  IMoveTaskRequest,
  IMoveTaskResponse,
  IUpdateTaskRequest,
  IUpdateTaskResponse,
  ICreateTaskProjectRequest,
  ICreateTaskProjectResponse,
  IGetTaskProjectsRequest,
  IGetTaskProjectsResponse,
  IGetTaskProjectResponse,
} from './task.types';

class TaskService {
  // ── Tasks ──

  async getTasks(params: IGetTasksRequest = {}): Promise<IGetTasksResponse> {
    return await api.get('/v1/tasks', { params });
  }

  async getTask(id: number): Promise<IGetTaskResponse> {
    return await api.get(`/v1/tasks/${id}`);
  }

  async createTask(data: ICreateTaskRequest): Promise<ICreateTaskResponse> {
    return await api.post('/v1/tasks', data);
  }

  async updateTask({ id, data }: IUpdateTaskRequest): Promise<IUpdateTaskResponse> {
    return await api.put(`/v1/tasks/${id}`, data);
  }

  async deleteTask({ id }: IDeleteTaskRequest): Promise<IDeleteTaskResponse> {
    return await api.delete(`/v1/tasks/${id}`);
  }

  async moveTask(data: IMoveTaskRequest): Promise<IMoveTaskResponse> {
    return await api.post('/v1/tasks/move', data);
  }

  async getTasksKanban(): Promise<IGetTasksKanbanResponse> {
    return await api.get('/v1/tasks/kanban');
  }

  async getTasksDashboard(): Promise<IGetTaskDashboardResponse> {
    return await api.get('/v1/tasks/dashboard');
  }

  // ── Projects ──

  async getTaskProjects(params: IGetTaskProjectsRequest = {}): Promise<IGetTaskProjectsResponse> {
    return await api.get('/v1/task-projects', { params });
  }

  async getTaskProject(id: number): Promise<IGetTaskProjectResponse> {
    return await api.get(`/v1/task-projects/${id}`);
  }

  async createTaskProject(data: ICreateTaskProjectRequest): Promise<ICreateTaskProjectResponse> {
    return await api.post('/v1/task-projects', data);
  }

  async updateTaskProject(id: number, data: Partial<ICreateTaskProjectRequest>): Promise<ICreateTaskProjectResponse> {
    return await api.put(`/v1/task-projects/${id}`, data);
  }

  async deleteTaskProject(id: number): Promise<void> {
    return await api.delete(`/v1/task-projects/${id}`);
  }

  // ── Subtasks ──

  async createSubtask(taskId: number, data: { text: string }): Promise<any> {
    return await api.post(`/v1/tasks/${taskId}/subtasks`, data);
  }

  async updateSubtask(taskId: number, subtaskId: number, data: { text?: string; is_done?: boolean }): Promise<any> {
    return await api.put(`/v1/tasks/${taskId}/subtasks/${subtaskId}`, data);
  }

  async deleteSubtask(taskId: number, subtaskId: number): Promise<void> {
    return await api.delete(`/v1/tasks/${taskId}/subtasks/${subtaskId}`);
  }

  // ── Comments ──

  async getComments(taskId: number): Promise<any> {
    return await api.get(`/v1/tasks/${taskId}/comments`);
  }

  async createComment(taskId: number, data: { text: string }): Promise<any> {
    return await api.post(`/v1/tasks/${taskId}/comments`, data);
  }

  // ── Time entries ──

  async getTimeEntries(taskId: number): Promise<any> {
    return await api.get(`/v1/tasks/${taskId}/time-entries`);
  }

  async createTimeEntry(taskId: number, data: { minutes: number; description?: string; date: string }): Promise<any> {
    return await api.post(`/v1/tasks/${taskId}/time-entries`, data);
  }

  // ── Lookups ──

  async getStatuses(): Promise<any> {
    return await api.get('/v1/task-statuses');
  }

  async getPriorities(): Promise<any> {
    return await api.get('/v1/task-priorities');
  }

  async getCategories(): Promise<any> {
    return await api.get('/v1/task-categories');
  }

  async getTags(): Promise<any> {
    return await api.get('/v1/task-tags');
  }

  async getTemplates(): Promise<any> {
    return await api.get('/v1/task-templates');
  }

  async applyTemplate(templateId: number, projectId?: number): Promise<any> {
    return await api.post(`/v1/task-templates/${templateId}/apply`, { project_id: projectId });
  }
}

export const taskService = new TaskService();
