import {http} from "./httpService";

export const jobApplications = (page) => {
    return http().get('rh/jobs?page='+page);
};
