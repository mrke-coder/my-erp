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
