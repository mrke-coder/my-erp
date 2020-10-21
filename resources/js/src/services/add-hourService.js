import {http} from "./httpService";

export const employees = () => {
    return http().get('rh/all_employees');
};

export const add_hours =  (page) => {
    return http().get('rh/add_hours?page='+page);
};

export const post_add_hour = (add_hour) =>{
  return http().post('rh/add_hours', add_hour);
};

export const add_hour  = (id) => {
  return http().get('rh/add_hours/'+id);
};

export const update_add_hour = (add_hour) => {
  return http().put('rh/add_hours/'+add_hour.id, add_hour);
};

export const delete_add_hour = (id) => {
  return http().delete('rh/add_hours/'+id);
};

export const restore_add_hour = (id) => {
    return http().get('rh/restore/add_hour/'+id);
};
