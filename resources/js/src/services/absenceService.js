import {http} from "./httpService";

export const employees = () => {
    return http().get('rh/all_employees');
};

export const absences = (page) => {
    return http().get('rh/absences?page='+page);
};

export const post_absence = (absence) => {
  return http().post('rh/absences', absence);
};

export const absence = (id) => {
return   http().get('rh/absences/'+id);
};

export const update_absence = (id, absence) => {
  return http().put('rh/absences/'+id, absence);
};

export const delete_absence = (id) => {
  return http().delete('rh/absence/'+id);
};
