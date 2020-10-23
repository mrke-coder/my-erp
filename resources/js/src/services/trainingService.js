import {http} from "./httpService";

export const employees = () => {
    return http().get('rh/all_employees');
};

export const trainings = (page) => {
    return http().get('rh/trainings?page='+page);
};

export const post_training = (training) => {
  return http().post('rh/trainings', training);
};

export const training = id => {
    return http().get('rh/trainings/'+id);
};

export const update_training = (training) => {
    return http().put('rh/trainings/'+training.id, training);
};

export const delete_training = (id) => {
  return http().delete('rh/trainings/'+id);
};

export const restore_training = (id) => {
    return http().get('rh/restore/training/'+id);
};
