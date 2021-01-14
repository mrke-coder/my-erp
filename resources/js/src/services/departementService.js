import {http, httpFile} from "./httpService"

export const departements = (page) => {
  return http().get('rh/departments?page='+page);
}

export const post_departement = (service)=> {
  return http().post('rh/departments', service);
}

export const show_departement = (id) => {
  return http().get('rh/departments/'+id)
}

export const update_departement = (id,dept) => {
  return http().put('rh/departments/'+id, dept);
}

export const delete_departement = (id) => {
  return http().delete('rh/departments/'+id);
}

export const restore_deleted = (id) => {
  return http().get('rh/restore/department/'+id);
}

export const employees_by_department = id => {
  return http().get(`rh/all_emplyees_by_department/${id}`);
}