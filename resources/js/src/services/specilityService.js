import {http, httpFile} from "./httpService";

export const departements = () => {
    return http().get('rh/all_departement');
}
export const specialities = (page) => {
  return http().get('rh/specialities?page='+page);
}

export const speciality = (id) => {
    return http().get('rh/specialities/'+id);
}

export const delete_speciality = (id) => {
    return http().delete('rh/specialities/'+id);
}

export  const restore_trushedSpecialiy = (id) => {
    return http().get('rh/restore/speciality/'+id);
}
export const post_speciality = (speciality) => {
  return http().post('rh/specialities', speciality);
}
