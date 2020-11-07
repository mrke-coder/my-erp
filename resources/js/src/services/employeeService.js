import {http} from './httpService';

export const specialities = () => {
    return http().get('rh/all_specialities');
};

export const employees = (page) => {
    return http().get('rh/employees?page='+page);
};

export const post_employee = (employee) => {
    return http().post('rh/employees', employee)
};

export const employee = (id) => {
    return http().get('rh/employees/'+id);
};

export const update_employee = (id,employee) => {
    return http().put('rh/employees/'+id, employee);
};

export const delete_employee = (id) => {
    return http().delete('rh/employees/'+id);
};

export const restore_employee = (id) => {
    return http().get('rh/employees/'+id+'/delete');
};

export const details_employee = (id) => {
    return http().get('rh/employeeDetails/'+id);
};
