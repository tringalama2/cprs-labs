export default class UnparsableResult {
    constructor(result) {
        this.result = result;
    }

    result() {
        return {
            result: this.result,
        };
    }
}

