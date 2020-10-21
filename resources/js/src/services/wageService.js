import {http} from "./httpService";

export const employees = () => {
   return http().get('rh/all_employees');
};

export const wages = (page) =>{
  return   http().get('rh/wags?page=' + page);
};

export const post_wage = (wage) => {
    return http().post('rh/wags',wage);
};

export const wage = (id) => {
  return http().get('rh/wags/'+id);
};

export const update_wage = (id, wage) => {
    return http().put('rh/wags/'+id, wage);
};

export const delete_wage = (id) => {
    return http().delete('rh/wags/'+id);
};

export const restore_wage = (id) => {
    return http().get('rh/wages/'+id+'/restore')
};
