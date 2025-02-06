import ResultsBuilder from "./results-builder.js";
import DisplayBuilder from "./display-builder.js";


class LabDirector {
    constructor(rawLabString) {
        this._resultBuilder = new ResultsBuilder(rawLabString);
        this._resultBuilder.prepRows();
        this._resultBuilder.build();
        this._resultBuilder.sortLabs();
        this.labResults = this._resultBuilder.getLabResults();
        this.unparsableRows = this._resultBuilder.getUnparsableRows();
    }

    // The static async factory function pattern allows us to emulate asynchronous
    // constructors in JavaScript. At the core of this pattern is the indirect
    // invocation of constructor. The indirection enforces that any parameters
    // passed into the constructor are ready and correct at the type-level.
    // It is literally deferred initialization and a level of indirection.
    // https://dev.to/somedood/the-proper-way-to-write-async-constructors-in-javascript-1o8c
    static initialize(rawLabs) {
        const _labDirector = new LabDirector(rawLabs);
        const _displayBuilder = new DisplayBuilder(_labDirector.labResults);

        _labDirector.labels = _displayBuilder.getLabels();
        _labDirector.panels = _displayBuilder.getPanels();
        _labDirector.dateTimeHeaders = _displayBuilder.getDateTimeHeaders();

        return _labDirector;
    }
}

// async function LabDirector(rawLabs) {
//
//     const resultBuilder = new ResultsBuilder(rawLabs);
//     resultBuilder.prepRows();
//     resultBuilder.build();
//     resultBuilder.sortLabs();
//
//     this.labResults = resultBuilder.getLabResults();
//     this.unparsableRows = resultBuilder.getUnparsableRows();
//
//     const displayBuilder = new DisplayBuilder(resultBuilder.getLabResults());
//     this.labels = await displayBuilder.getLabels(); // ? await
//     this.panels = await displayBuilder.getPanels(); // ? await
//     this.dateTimeHeaders = displayBuilder.getDateTimeHeaders();
// }

// Todo:
//log unmatched labs w/ fetch API or Inertia
// import { router } from '@inertiajs/vue3'
//
// router.post('/users', {
//     name: 'John Doe',
//     email: 'john.doe@example.com',
// })

export {LabDirector};
