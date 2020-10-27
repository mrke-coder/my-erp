import {http} from "./httpService";

export const employees = () => {
    return http().get('rh/all_employees');
};

export const departures = (page) => {
  return http().get('rh/departures?page='+page);
};

export const post_departure = (departure) => {
  return http().post('rh/departures', departure);
};

export const departure = (id) => {
  return http().get('rh/departures/'+id);
};

export const update_departure = (departure) => {
  return http().put('rh/departures/'+departure.id, departure);
};

export const delete_departure = (id) => {
  return http().delete('rh/departures/'+id);
};

export const restore_departure = (id) => {
    return http().get('rh/restore/departure/'+id);
};
