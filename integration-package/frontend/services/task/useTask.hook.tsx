import { QUERY_KEYS } from '@/config/query-keys.config';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { toast } from 'sonner';
import { useTranslations } from 'next-intl';
import { taskService } from './task.service';
import { IGetTasksRequest } from './task.types';

// ══════════════════════════════════════════
// Tasks
// ══════════════════════════════════════════

export const useGetTasks = (params: IGetTasksRequest = {}) => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK.GET, params],
    queryFn: () => taskService.getTasks(params),
    select: data => data.data,
  });
};

export const useGetTasksAll = () => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK.GET_ALL],
    queryFn: () => taskService.getTasks({ limit: 10000 }),
    select: data => data.data,
  });
};

export const useGetTasksKanban = () => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK.GET, 'kanban'],
    queryFn: () => taskService.getTasksKanban(),
  });
};

export const useGetTasksDashboard = () => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK.DASHBOARD],
    queryFn: () => taskService.getTasksDashboard(),
    select: data => data.data,
  });
};

export const useGetTask = (id: number) => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK.GET_ONE, id],
    queryFn: () => taskService.getTask(id),
    select: data => data.data?.data,
    enabled: !!id && id > 0,
  });
};

export const useCreateTask = () => {
  const queryClient = useQueryClient();
  const t = useTranslations();

  return useMutation({
    mutationKey: [QUERY_KEYS.TASK.CREATE],
    mutationFn: taskService.createTask,
    onMutate: () => {
      toast.loading(t('toast.taskCreating'));
    },
    onSuccess: () => {
      toast.dismiss();
      toast.success(t('toast.taskCreated'));
      queryClient.invalidateQueries({ queryKey: [QUERY_KEYS.TASK.GET] });
    },
    onError: () => {
      toast.dismiss();
      toast.error(t('toast.taskCreateError'));
    },
  });
};

export const useUpdateTask = (id: number) => {
  const queryClient = useQueryClient();
  const t = useTranslations();

  return useMutation({
    mutationKey: [QUERY_KEYS.TASK.UPDATE, id],
    mutationFn: taskService.updateTask,
    onMutate: () => {
      toast.loading(t('toast.taskUpdating'));
    },
    onSuccess: data => {
      toast.dismiss();
      toast.success(t('toast.taskUpdated'));
      if (data?.data?.data?.id) {
        queryClient.invalidateQueries({
          queryKey: [QUERY_KEYS.TASK.GET_ONE, data.data.data.id],
        });
      }
      queryClient.invalidateQueries({ queryKey: [QUERY_KEYS.TASK.GET] });
    },
    onError: () => {
      toast.dismiss();
      toast.error(t('toast.taskUpdateError'));
    },
  });
};

export const useDeleteTask = () => {
  const queryClient = useQueryClient();
  const t = useTranslations();

  return useMutation({
    mutationKey: [QUERY_KEYS.TASK.DELETE],
    mutationFn: taskService.deleteTask,
    onMutate: () => {
      toast.loading(t('toast.taskDeleting'));
    },
    onSuccess: () => {
      toast.dismiss();
      toast.success(t('toast.taskDeleted'));
      queryClient.invalidateQueries({ queryKey: [QUERY_KEYS.TASK.GET] });
    },
    onError: () => {
      toast.dismiss();
      toast.error(t('toast.taskDeleteError'));
    },
  });
};

export const useMoveTask = () => {
  return useMutation({
    mutationKey: [QUERY_KEYS.TASK.MOVE],
    mutationFn: taskService.moveTask,
  });
};

// ══════════════════════════════════════════
// Projects
// ══════════════════════════════════════════

export const useGetTaskProjects = () => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK_PROJECT.GET],
    queryFn: () => taskService.getTaskProjects(),
    select: data => data.data,
  });
};

export const useGetTaskProject = (id: number) => {
  return useQuery({
    queryKey: [QUERY_KEYS.TASK_PROJECT.GET_ONE, id],
    queryFn: () => taskService.getTaskProject(id),
    select: data => data.data?.data,
    enabled: !!id && id > 0,
  });
};

export const useCreateTaskProject = () => {
  const queryClient = useQueryClient();
  const t = useTranslations();

  return useMutation({
    mutationKey: [QUERY_KEYS.TASK_PROJECT.CREATE],
    mutationFn: taskService.createTaskProject,
    onSuccess: () => {
      toast.success(t('toast.projectCreated'));
      queryClient.invalidateQueries({ queryKey: [QUERY_KEYS.TASK_PROJECT.GET] });
    },
    onError: () => {
      toast.error(t('toast.projectCreateError'));
    },
  });
};
