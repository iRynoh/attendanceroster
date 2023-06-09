import ModelService from "@/services/ModelService";

export default class StudentService extends ModelService {

    constructor() {
        super();
        this.url = '/students';
    }
}
