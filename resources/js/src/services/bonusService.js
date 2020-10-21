import {http} from "./httpService";

export const employees = () => {
    return http().get('rh/all_employees');
};

export const bonuses = (page) => {
    return http().get('rh/bonuses?page='+page);
};

export const post_bonus = (bonus) => {
  return http().post('rh/bonuses',bonus);
};

export const bonus = (id) => {
    return http().get('rh/bonuses/'+id);
};

export const update_bonus = (id, bonus) => {
  return http().put('rh/bonuses/'+id, bonus);
};

export const delete_bonus = (id) => {
  return http().delete('rh/bonuses/'+id);
};

export const restore_bonus = (id) => {
    return http().get('rh/bonus/'+id);
};
