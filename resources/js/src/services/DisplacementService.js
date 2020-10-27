import {http} from "./httpService";

export const employees = () => {
  return http().get('rh/all_employees');
};

export const displacements = (page) => {
  return http().get('rh/displacements?page='+page);
};

export const post_displacement = (displacement) => {
    return http().post('rh/displacements', displacement);
};

export const displacement = (id) => {
   return http().get('rh/displacements/'+id);
};

export const update_displacement = (displacement) => {
  return http().put('rh/displacements/'+displacement.id, displacement);
};

export const delete_displacement = (id) => {
    return http().delete('rh/displacements/'+id);
};

export const restore_displacement = (id) => {
    return http().get('rh/restore/displacement/'+id);
};
