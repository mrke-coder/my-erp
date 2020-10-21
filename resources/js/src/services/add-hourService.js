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
