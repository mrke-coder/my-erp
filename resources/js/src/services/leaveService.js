import {http} from "./httpService";

export const employees = () => {
  return http().get('rh/all_employees');
};

export const permissions = (page) => {
    return http().get('rh/leaves?page='+page);
};

export const post_permission = (leave) => {
    return http().post('rh/leaves', leave);
};

export const permission = (id) => {
    return http().get('rh/leaves/'+id);
};

export const update_permission = (leave) => {
  return http().put('rh/leaves/'+leave.id, leave);
};

export const delete_permission = (id) => {
  return http().delete('rh/leaves/'+id);
};

export const restore_leave = (id) => {
  return http().get('rh/restore/leave/'+id);
};
